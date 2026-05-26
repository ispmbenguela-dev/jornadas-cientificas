<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class InscricaoController extends Controller
{
    private const SIGAM_VERIFY_URL = 'https://sigam.ispm.online/api/verify-role';
    private const SIGAM_DOCENTE_ROLE = 'teacher';

    public function create(): View
    {
        return view('inscricao.create', [
            'categorias'           => Inscricao::CATEGORIAS,
            'modalidades'          => Inscricao::MODALIDADES,
            'precos'               => Inscricao::TABELA_PRECOS,
            'miniCursos'           => Inscricao::MINI_CURSOS,
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

        $isDocente = (bool) ($resultado['is_docente'] ?? false);
        $exists    = (bool) ($resultado['exists'] ?? false);

        return response()->json([
            'ok'         => true,
            'exists'     => $exists,
            'is_docente' => $isDocente,
            'roles'      => $resultado['roles'] ?? [],
            'user'       => $resultado['user'] ?? null,
            'message'    => $isDocente
                ? 'Docente verificado. Tem direito a um mini-curso gratuito.'
                : ($exists ? 'Utilizador encontrado, mas não tem o papel de docente.' : 'E-mail não encontrado no SIGAM.'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $instituicaoIspm = Inscricao::isInstituicaoIspm($request->input('instituicao'));
        $isDocenteContexto = $request->input('categoria') === 'docente' && $instituicaoIspm;

        $rules = [
            'nome'                  => ['required', 'string', 'max:160'],
            'email'                 => ['required', 'email', 'max:160'],
            'telefone'              => ['required', 'string', 'max:40'],
            'instituicao'           => ['nullable', 'string', 'max:160'],
            'categoria'             => ['required', 'in:docente,estudante,publico'],
            'modalidade'            => ['required', 'in:participacao,mini_curso'],
            'mini_cursos'           => ['nullable', 'array', 'required_if:modalidade,mini_curso'],
            'mini_cursos.*'         => ['string', 'in:' . implode(',', array_keys(Inscricao::MINI_CURSOS))],
            'email_institucional'   => [$isDocenteContexto ? 'required' : 'nullable', 'email', 'max:160'],
        ];

        $data = $request->validate($rules, [
            'mini_cursos.required_if'         => 'Seleccione pelo menos um mini-curso.',
            'email_institucional.required'    => 'Informe o e-mail institucional para verificação no sistema.',
        ]);

        $isDocenteIspm = false;
        $verificacaoPayload = null;

        if ($isDocenteContexto && !empty($data['email_institucional'])) {
            $verificacaoPayload = $this->consultarSigam($data['email_institucional']);
            $isDocenteIspm = (bool) ($verificacaoPayload['is_docente'] ?? false);
        }

        if ($data['modalidade'] !== 'mini_curso') {
            $data['mini_cursos'] = null;
            $quantidade = 0;
        } else {
            $data['mini_cursos'] = array_values(array_unique($data['mini_cursos'] ?? []));
            $quantidade = count($data['mini_cursos']);
        }

        $data['is_docente_ispm']   = $isDocenteIspm;
        $data['verificacao_sigam'] = $verificacaoPayload;
        $data['valor_kz']          = Inscricao::calcularValor($data['categoria'], $data['modalidade'], $quantidade, $isDocenteIspm);

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

        $inscricao = Inscricao::create($data);

        $msg = 'Inscrição registada com sucesso.';
        if ($inscricao->validacao_pagamento === 'divergente') {
            $msg .= ' Atenção: o valor declarado (' . number_format($inscricao->valor_pago_informado, 0, ',', '.')
                 . ' Kz) não coincide com o valor calculado (' . number_format($inscricao->valor_kz, 0, ',', '.')
                 . ' Kz). A Comissão Científica irá conferir.';
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
