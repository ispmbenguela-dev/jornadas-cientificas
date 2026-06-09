<?php

namespace App\Http\Controllers;

use App\Mail\InscricaoConfirmada;
use App\Models\Configuracao;
use App\Models\Edicao;
use App\Models\Inscricao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class InscricaoController extends Controller
{
    private const SIGAM_VERIFY_URL = 'https://sigam.ispm.online/api/verify-role';
    private const SIGAM_DOCENTE_ROLE = 'teacher';

    public function create(): View
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();

        if (!$this->inscricoesAbertas($edicao)) {
            return view('inscricao.fechada', compact('edicao'));
        }

        $taxas = $edicao?->taxas ?? Inscricao::TABELA_PRECOS;

        return view('inscricao.create', [
            'edicao'               => $edicao,
            'categorias'           => Inscricao::CATEGORIAS,
            'modalidades'          => Inscricao::MODALIDADES,
            'precos'               => $taxas,
            'miniCursos'           => Inscricao::miniCursosDisponiveis($edicao),
            'instituicoesIspm'     => Inscricao::INSTITUICAO_ISPM_ALIASES,
        ]);
    }

    public function verificarDocente(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:160'],
        ]);

        $resultado = $this->consultarSigam($data['email']);

        if (!($resultado['ok'] ?? false)) {
            $debug = config('app.debug');
            return response()->json([
                'ok'      => false,
                'reason'  => $resultado['reason'] ?? 'sigam_unreachable',
                'message' => $resultado['message'] ?? 'Não foi possível contactar o SIGAM.',
                'debug'   => $debug ? ($resultado['debug'] ?? null) : null,
            ], $resultado['status'] ?? 503);
        }

        $isDocente   = (bool) ($resultado['is_docente'] ?? false);
        $exists      = (bool) ($resultado['exists'] ?? false);
        $beneficioUsado = false;

        if ($isDocente && Inscricao::jaUsouBeneficioIspm($data['email'])) {
            $beneficioUsado = true;
            $isDocente      = false; // remove o benefício gratuito
        }

        if ($beneficioUsado) {
            $message = 'Docente confirmado, mas o benefício gratuito já foi usado numa inscrição anterior. Esta inscrição será cobrada como participante normal.';
        } elseif ($isDocente) {
            $message = 'Docente verificado. Tem direito a um mini-curso gratuito.';
        } elseif ($exists) {
            $message = 'Utilizador encontrado, mas não tem o papel de docente.';
        } else {
            $message = 'E-mail não encontrado no SIGAM.';
        }

        return response()->json([
            'ok'              => true,
            'exists'          => $exists,
            'is_docente'      => $isDocente,
            'beneficio_usado' => $beneficioUsado,
            'roles'           => $resultado['roles'] ?? [],
            'user'            => $resultado['user'] ?? null,
            'message'         => $message,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();

        if (!$this->inscricoesAbertas($edicao)) {
            return redirect()->route('inscricao.create')
                ->with('error', 'As inscrições estão encerradas.');
        }

        $miniCursosCatalogo = Inscricao::miniCursosDisponiveis($edicao);

        $instituicaoIspm = Inscricao::isInstituicaoIspm($request->input('instituicao'));
        $isDocenteContexto = $request->input('categoria') === 'docente' && $instituicaoIspm;

        $rules = [
            'nome'                  => ['required', 'string', 'max:160'],
            'email'                 => ['required', 'email', 'max:160'],
            'telefone'              => ['required', 'string', 'max:40'],
            'instituicao'           => ['nullable', 'string', 'max:160'],
            'categoria'             => ['required', 'in:' . implode(',', array_keys(Inscricao::CATEGORIAS))],
            'modalidade'            => ['required', 'in:participacao,mini_curso'],
            'mini_cursos'           => ['nullable', 'array', 'required_if:modalidade,mini_curso'],
            'mini_cursos.*'         => ['string', 'in:' . implode(',', array_keys($miniCursosCatalogo))],
            'email_institucional'   => [$isDocenteContexto ? 'required' : 'nullable', 'email', 'max:160'],
        ];

        $data = $request->validate($rules, [
            'mini_cursos.required_if'         => 'Seleccione pelo menos um mini-curso.',
            'email_institucional.required'    => 'Informe o e-mail institucional para verificação no sistema.',
        ]);

        // MCO: gratuito, mas deve estar pré-registado pelo admin (nome + e-mail)
        if ($data['categoria'] === 'mco') {
            $membro = Inscricao::where('categoria', 'mco')
                ->whereRaw('LOWER(email) = LOWER(?)', [$data['email']])
                ->whereRaw('LOWER(nome) = LOWER(?)', [trim($data['nome'])])
                ->first();

            if (!$membro) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'email' => ['O nome e e-mail introduzidos não constam da lista da Comissão Organizadora. Contacte a organização.'],
                ]);
            }

            // Actualiza o registo existente com os dados que o utilizador forneceu
            $membro->update([
                'telefone'    => $data['telefone'],
                'instituicao' => $data['instituicao'] ?? $membro->instituicao,
                'edicao_id'   => $edicao?->id ?? $membro->edicao_id,
                'estado'      => $membro->estado === 'pendente' ? 'confirmada' : $membro->estado,
            ]);

            try {
                Mail::to($membro->email)->send(new InscricaoConfirmada($membro));
            } catch (\Throwable $e) {
                Log::warning('Falha ao enviar e-mail de confirmação MCO', [
                    'inscricao_id' => $membro->id,
                    'error'        => $e->getMessage(),
                ]);
            }

            return redirect()->route('inscricao.sucesso', $membro);
        }

        $isDocenteIspm = false;
        $verificacaoPayload = null;
        $beneficioUsado = false;

        if ($isDocenteContexto && !empty($data['email_institucional'])) {
            $verificacaoPayload = $this->consultarSigam($data['email_institucional']);
            $isDocenteIspm = (bool) ($verificacaoPayload['is_docente'] ?? false);

            // Cada docente ISPM só pode usar o benefício gratuito uma vez.
            if ($isDocenteIspm && Inscricao::jaUsouBeneficioIspm($data['email_institucional'])) {
                $isDocenteIspm  = false;
                $beneficioUsado = true;
                if (is_array($verificacaoPayload)) {
                    $verificacaoPayload['beneficio_usado'] = true;
                }
            }
        }

        $miniCursosEnviados = array_values(array_unique($data['mini_cursos'] ?? []));

        if ($data['modalidade'] === 'mini_curso') {
            $data['mini_cursos'] = $miniCursosEnviados;
            $quantidade = count($miniCursosEnviados);
        } elseif ($data['modalidade'] === 'participacao' && $isDocenteIspm) {
            // Docente ISPM em modo Participação tem direito a 1 mini-curso gratuito.
            // Exigimos exactamente 1; se enviar mais, limitamos.
            if (count($miniCursosEnviados) === 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'mini_cursos' => ['Como docente ISPM, escolha 1 mini-curso gratuito.'],
                ]);
            }
            $data['mini_cursos'] = [reset($miniCursosEnviados)];
            $quantidade = 0; // mini-curso bónus → não contribui para o valor
        } else {
            $data['mini_cursos'] = null;
            $quantidade = 0;
        }

        $data['edicao_id']         = $edicao?->id;
        $data['is_docente_ispm']   = $isDocenteIspm;
        $data['verificacao_sigam'] = $verificacaoPayload;
        $data['valor_kz']          = Inscricao::calcularValor($data['categoria'], $data['modalidade'], $quantidade, $isDocenteIspm, $edicao);

        if ($data['valor_kz'] > 0) {
            $pago = $request->validate([
                'valor_pago_informado' => ['required', 'integer', 'min:0'],
                'referencia_pagamento' => ['required', 'string', 'max:80'],
                'comprovativo'         => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ], [
                'valor_pago_informado.required' => 'Informe o valor pago no comprovativo.',
                'referencia_pagamento.required' => 'Informe a referência do depósito/transferência.',
                'comprovativo.required'         => 'Anexe o comprovativo de pagamento.',
            ]);

            $data['valor_pago_informado'] = (int) $pago['valor_pago_informado'];
            $data['referencia_pagamento'] = $pago['referencia_pagamento'];
            $data['validacao_pagamento']  = $data['valor_pago_informado'] === $data['valor_kz'] ? 'ok' : 'divergente';
        } else {
            $data['valor_pago_informado'] = null;
            $data['referencia_pagamento'] = null;
            $data['validacao_pagamento']  = 'nao_aplicavel';
        }

        if ($request->hasFile('comprovativo')) {
            $data['comprovativo_path'] = $request->file('comprovativo')->store('comprovativos', 'public');
        }

        if ($data['categoria'] === 'pta') {
            $request->validate([
                'cracha' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            ], [
                'cracha.required' => 'O crachá de funcionário é obrigatório para Pessoal Técnico Administrativo.',
                'cracha.mimes'    => 'O crachá deve ser um ficheiro PDF, JPG ou PNG.',
                'cracha.max'      => 'O crachá não pode exceder 5 MB.',
            ]);
            $data['cracha_path'] = $request->file('cracha')->store('crachas', 'public');
        }

        $inscricao = Inscricao::create($data);

        try {
            Mail::to($inscricao->email)->send(new InscricaoConfirmada($inscricao));
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar e-mail de confirmação de inscrição', [
                'inscricao_id' => $inscricao->id,
                'email'        => $inscricao->email,
                'error'        => $e->getMessage(),
            ]);
        }

        $msg = 'Inscrição registada com sucesso. Enviámos um e-mail de confirmação para ' . $inscricao->email . '.';
        if ($inscricao->validacao_pagamento === 'divergente') {
            $msg .= ' Atenção: o valor declarado (' . number_format($inscricao->valor_pago_informado, 0, ',', '.')
                 . ' Kz) não coincide com o valor calculado (' . number_format($inscricao->valor_kz, 0, ',', '.')
                 . ' Kz). A Comissão Científica irá conferir.';
        } elseif ($beneficioUsado) {
            $msg .= ' Nota: o benefício de docente ISPM já tinha sido usado numa inscrição anterior — esta foi cobrada como participação normal.';
        } elseif ($isDocenteContexto && !$isDocenteIspm) {
            $msg .= ' A verificação no sistema não confirmou docente — a Comissão pode pedir comprovativo de vínculo.';
        }

        return redirect()
            ->route('inscricao.sucesso', $inscricao)
            ->with('success', $msg);
    }

    public function sucesso(Inscricao $inscricao): View
    {
        return view('inscricao.sucesso', compact('inscricao'));
    }

    private function inscricoesAbertas(?Edicao $edicao): bool
    {
        if (Configuracao::get('inscricao_forcar_fechado') === '1') {
            return false;
        }
        // Usa as datas da edição se existirem, senão cai na data global de configuração
        if ($edicao && ($edicao->inscricao_fim || $edicao->inscricao_inicio)) {
            return $edicao->inscricaoAberta();
        }
        $prazo = Configuracao::get('inscricao_prazo');
        if (!$prazo) {
            return false;
        }
        return now()->lte(\Carbon\Carbon::parse($prazo)->endOfDay());
    }

    private function consultarSigam(string $email): array
    {
        $verifySsl  = filter_var(env('SIGAM_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN);
        $tentativas = $verifySsl ? [true, false] : [false];
        $ultimoErro = null;
        $debug      = [];

        foreach ($tentativas as $verify) {
            try {
                $client = Http::timeout(15)
                    ->connectTimeout(8)
                    ->acceptJson()
                    ->asJson()
                    ->withHeaders(['User-Agent' => 'JornadasISPM/1.0 (+laravel)']);

                if (!$verify) {
                    $client = $client->withoutVerifying();
                }

                $response = $client->post(self::SIGAM_VERIFY_URL, [
                    'email' => $email,
                    'role'  => self::SIGAM_DOCENTE_ROLE,
                ]);

                if (!$response->ok()) {
                    return [
                        'ok'      => false,
                        'reason'  => 'sigam_error',
                        'status'  => 502,
                        'message' => 'O SIGAM respondeu com erro ' . $response->status() . '.',
                        'debug'   => ['http_status' => $response->status(), 'body' => mb_substr((string) $response->body(), 0, 400)],
                    ];
                }

                $payload = $response->json();
                if (!is_array($payload)) {
                    return [
                        'ok'      => false,
                        'reason'  => 'sigam_invalid_json',
                        'status'  => 502,
                        'message' => 'O SIGAM devolveu uma resposta inválida.',
                        'debug'   => ['body' => mb_substr((string) $response->body(), 0, 400)],
                    ];
                }

                $exists    = (bool) ($payload['exists'] ?? false);
                $verified  = (bool) ($payload['verified'] ?? false);
                $isDocente = $exists && $verified;
                $roles     = collect($payload['user_roles'] ?? [])
                    ->pluck('name')
                    ->map(fn($n) => strtolower((string) $n))
                    ->all();

                return [
                    'ok'              => true,
                    'exists'          => $exists,
                    'is_docente'      => $isDocente,
                    'role_solicitado' => self::SIGAM_DOCENTE_ROLE,
                    'roles'           => $roles,
                    'user'            => $payload['user'] ?? null,
                    'raw'             => $payload,
                    'verified_at'     => now()->toIso8601String(),
                    'ssl_verify_used' => $verify,
                ];
            } catch (\Throwable $e) {
                $ultimoErro = $e;
                $debug['attempts'][] = [
                    'verify_ssl' => $verify,
                    'class'      => get_class($e),
                    'message'    => $e->getMessage(),
                ];
                Log::warning('SIGAM verify-role attempt failed', [
                    'email'      => $email,
                    'verify_ssl' => $verify,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        $sslHint = $ultimoErro && str_contains(strtolower($ultimoErro->getMessage()), 'ssl')
            ? ' (parece ser SSL/CA — defina SIGAM_VERIFY_SSL=false no .env e tente de novo)'
            : '';

        return [
            'ok'      => false,
            'reason'  => 'sigam_unreachable',
            'status'  => 503,
            'message' => 'Não foi possível contactar o SIGAM.' . $sslHint,
            'debug'   => $debug,
        ];
    }
}
