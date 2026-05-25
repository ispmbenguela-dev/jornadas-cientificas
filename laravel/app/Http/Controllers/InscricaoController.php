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
    private const SIGAM_VERIFY_URL = 'https://sigam.ispm.online/api/verify-user';

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

        try {
            $response = Http::timeout(8)
                ->acceptJson()
                ->get(self::SIGAM_VERIFY_URL, ['email' => $data['email']]);
        } catch (\Throwable $e) {
            Log::warning('SIGAM verify failed', ['email' => $data['email'], 'error' => $e->getMessage()]);
            return response()->json([
                'ok'      => false,
                'reason'  => 'sigam_unreachable',
                'message' => 'Não foi possível contactar. Tente novamente em alguns segundos.',
            ], 503);
        }

        if (!$response->ok()) {
            return response()->json([
                'ok'      => false,
                'reason'  => 'sigam_error',
                'message' => 'Retornou erro ' . $response->status() . '. Verifique o e-mail e tente novamente.',
            ], 502);
        }

        $payload = $response->json();
        $exists  = (bool) ($payload['exists'] ?? false);
        $profile = $payload['profile'] ?? null;
        $roles   = collect($payload['roles'] ?? [])->pluck('name')->map(fn($n) => strtolower((string) $n))->all();

        $isDocente = $exists && (
            $profile === 'docente'
            || in_array('teacher', $roles, true)
            || in_array('professor', $roles, true)
            || in_array('docente', $roles, true)
        );

        return response()->json([
            'ok'         => true,
            'exists'     => $exists,
            'is_docente' => $isDocente,
            'profile'    => $profile,
            'roles'      => $roles,
            'user'       => $payload['user'] ?? null,
            'raw'        => $payload,
            'message'    => $isDocente
                ? 'Docente verificado. Tem direito a um mini-curso gratuito.'
                : ($exists ? 'Utilizador encontrado, mas não é docente.' : 'E-mail não encontrado no Sistema.'),
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
        try {
            $response = Http::timeout(8)
                ->acceptJson()
                ->get(self::SIGAM_VERIFY_URL, ['email' => $email]);

            if (!$response->ok()) {
                return ['ok' => false, 'reason' => 'sigam_error', 'status' => $response->status()];
            }

            $payload = $response->json();
            $profile = $payload['profile'] ?? null;
            $roles   = collect($payload['roles'] ?? [])->pluck('name')->map(fn($n) => strtolower((string) $n))->all();
            $isDocente = (bool) ($payload['exists'] ?? false) && (
                $profile === 'docente'
                || in_array('teacher', $roles, true)
                || in_array('professor', $roles, true)
                || in_array('docente', $roles, true)
            );

            return [
                'ok'         => true,
                'exists'     => (bool) ($payload['exists'] ?? false),
                'is_docente' => $isDocente,
                'profile'    => $profile,
                'roles'      => $roles,
                'user'       => $payload['user'] ?? null,
                'raw'        => $payload,
                'verified_at' => now()->toIso8601String(),
            ];
        } catch (\Throwable $e) {
            Log::warning('SIGAM verify failed (store)', ['email' => $email, 'error' => $e->getMessage()]);
            return ['ok' => false, 'reason' => 'sigam_unreachable', 'error' => $e->getMessage()];
        }
    }
}
