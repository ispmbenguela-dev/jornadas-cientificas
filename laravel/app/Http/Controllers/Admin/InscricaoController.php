<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InscricaoEstadoAlterado;
use App\Models\Edicao;
use App\Models\Inscricao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class InscricaoController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filtered($request);
        $totalValor = (clone $query)->sum('valor_kz');
        $totalConfirmado = (clone $query)->where('estado', 'confirmada')->sum('valor_kz');

        return view('admin.inscricoes.index', [
            'inscricoes'       => $query->paginate(20)->withQueryString(),
            'categorias'       => Inscricao::CATEGORIAS,
            'totalValor'       => (int) $totalValor,
            'totalConfirmado'  => (int) $totalConfirmado,
        ]);
    }

    public function create(): View
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();
        return view('admin.inscricoes.create', [
            'edicao'     => $edicao,
            'categorias' => Inscricao::CATEGORIAS,
            'modalidades'=> Inscricao::MODALIDADES,
            'miniCursos' => Inscricao::miniCursosDisponiveis($edicao),
            'validacoes' => Inscricao::VALIDACAO_LABELS,
            'precos'     => $edicao?->taxas ?? Inscricao::TABELA_PRECOS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();
        $miniCursosCatalogo = Inscricao::miniCursosDisponiveis($edicao);

        $data = $request->validate([
            'nome'                 => ['required', 'string', 'max:160'],
            'email'                => ['required', 'email', 'max:160'],
            'telefone'             => ['required', 'string', 'max:40'],
            'instituicao'          => ['nullable', 'string', 'max:160'],
            'categoria'            => ['required', 'in:' . implode(',', array_keys(Inscricao::CATEGORIAS))],
            'modalidade'           => ['required', 'in:participacao,mini_curso'],
            'mini_cursos'          => ['nullable', 'array'],
            'mini_cursos.*'        => ['string', 'in:' . implode(',', array_keys($miniCursosCatalogo))],
            'is_docente_ispm'      => ['nullable', 'boolean'],
            'email_institucional'  => ['nullable', 'email', 'max:160'],
            'valor_kz'             => ['nullable', 'integer', 'min:0'],
            'valor_pago_informado' => ['nullable', 'integer', 'min:0'],
            'referencia_pagamento' => ['nullable', 'string', 'max:100'],
            'validacao_pagamento'  => ['nullable', 'in:' . implode(',', array_keys(Inscricao::VALIDACAO_LABELS))],
            'comprovativo'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'cracha'               => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'estado'               => ['required', 'in:pendente,confirmada,rejeitada'],
            'observacoes'          => ['nullable', 'string', 'max:1000'],
        ]);

        $data['is_docente_ispm'] = $request->boolean('is_docente_ispm');
        $data['mini_cursos']     = array_values(array_unique($data['mini_cursos'] ?? []));
        $data['edicao_id']       = $edicao?->id;

        // Calcula valor se não foi preenchido manualmente
        if (($data['valor_kz'] ?? null) === null) {
            $qtd = $data['modalidade'] === 'mini_curso' ? count($data['mini_cursos']) : 0;
            $data['valor_kz'] = Inscricao::calcularValor(
                $data['categoria'], $data['modalidade'], $qtd, $data['is_docente_ispm'], $edicao
            );
        }

        // Valida pagamento automaticamente ao comparar valores
        if (!($data['valor_kz'] ?? 0)) {
            $data['validacao_pagamento'] = 'nao_aplicavel';
        } elseif (isset($data['valor_pago_informado']) && $data['valor_pago_informado'] !== null) {
            $data['validacao_pagamento'] = ((int) $data['valor_pago_informado'] === (int) $data['valor_kz'])
                ? 'ok' : 'divergente';
        } else {
            $data['validacao_pagamento'] = $data['validacao_pagamento'] ?? 'pendente';
        }

        if ($request->hasFile('comprovativo')) {
            $data['comprovativo_path'] = $request->file('comprovativo')->store('comprovativos', 'public');
        }
        if ($request->hasFile('cracha')) {
            $data['cracha_path'] = $request->file('cracha')->store('crachas', 'public');
        }

        unset($data['comprovativo'], $data['cracha']);

        $inscricao = Inscricao::create($data);

        return redirect()->route('admin.inscricoes.show', $inscricao)
            ->with('success', 'Inscrição criada com sucesso.');
    }

    public function show(Inscricao $inscricao): View
    {
        return view('admin.inscricoes.show', compact('inscricao'));
    }

    public function edit(Inscricao $inscricao): View
    {
        $edicao = $inscricao->edicao ?? Edicao::query()->where('status', 'actual')->first();
        return view('admin.inscricoes.edit', [
            'inscricao'  => $inscricao,
            'categorias' => Inscricao::CATEGORIAS,
            'modalidades'=> Inscricao::MODALIDADES,
            'miniCursos' => Inscricao::miniCursosDisponiveis($edicao),
            'validacoes' => Inscricao::VALIDACAO_LABELS,
        ]);
    }

    public function update(Request $request, Inscricao $inscricao): RedirectResponse
    {
        if ($request->boolean('_full_edit')) {
            $miniCursoKeys = array_keys(Inscricao::miniCursosDisponiveis());
            $data = $request->validate([
                'nome'                 => ['required', 'string', 'max:160'],
                'email'                => ['required', 'email', 'max:160'],
                'telefone'             => ['required', 'string', 'max:40'],
                'instituicao'          => ['nullable', 'string', 'max:160'],
                'categoria'            => ['required', 'in:' . implode(',', array_keys(Inscricao::CATEGORIAS))],
                'modalidade'           => ['required', 'in:participacao,mini_curso'],
                'mini_cursos'          => ['nullable', 'array'],
                'mini_cursos.*'        => ['string', 'in:' . implode(',', $miniCursoKeys)],
                'valor_kz'             => ['nullable', 'integer', 'min:0'],
                'valor_pago_informado' => ['nullable', 'numeric', 'min:0'],
                'referencia_pagamento' => ['nullable', 'string', 'max:100'],
                'validacao_pagamento'  => ['nullable', 'in:' . implode(',', array_keys(Inscricao::VALIDACAO_LABELS))],
                'is_docente_ispm'      => ['nullable', 'boolean'],
                'email_institucional'  => ['nullable', 'email', 'max:160'],
                'estado'               => ['required', 'in:pendente,confirmada,rejeitada'],
                'observacoes'          => ['nullable', 'string', 'max:1000'],
            ]);
            $data['is_docente_ispm'] = $request->boolean('is_docente_ispm');
            $data['mini_cursos']     = $data['mini_cursos'] ?? [];
            $inscricao->update($data);
            return redirect()->route('admin.inscricoes.show', $inscricao)
                ->with('success', 'Inscrição actualizada.');
        }

        $data = $request->validate([
            'estado'      => ['required', 'in:pendente,confirmada,rejeitada'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ]);

        $estadoAnterior = $inscricao->estado;
        $inscricao->update($data);

        $msg = 'Inscrição actualizada.';
        try {
            Mail::to($inscricao->email)
                ->send(new InscricaoEstadoAlterado($inscricao, $estadoAnterior));
            $msg .= ' E-mail de notificação enviado para ' . $inscricao->email . '.';
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar e-mail de mudança de estado', [
                'inscricao_id'    => $inscricao->id,
                'estado_anterior' => $estadoAnterior,
                'estado_novo'     => $inscricao->estado,
                'email'           => $inscricao->email,
                'error'           => $e->getMessage(),
            ]);
            $msg .= ' (falhou o envio do e-mail — ver log)';
        }

        return back()->with('success', $msg);
    }

    public function destroy(Inscricao $inscricao): RedirectResponse
    {
        $inscricao->delete();
        return redirect()->route('admin.inscricoes.index')->with('success', 'Inscrição removida.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'inscricoes-' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->streamDownload(function () use ($request) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 para o Excel abrir acentos correctamente
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID', 'Data', 'Nome', 'Email', 'Telefone', 'Instituição',
                'Categoria', 'Docente ISPM', 'Email institucional',
                'Modalidade', 'Mini-cursos', '# Mini-cursos',
                'Valor calculado (Kz)', 'Valor declarado (Kz)', 'Referência', 'Validação',
                'Estado', 'Comprovativo (URL)', 'Observações',
            ], ';');

            $this->filtered($request)->chunk(500, function ($linha) use ($out) {
                foreach ($linha as $i) {
                    fputcsv($out, [
                        $i->id,
                        optional($i->created_at)->format('d/m/Y H:i'),
                        $i->nome,
                        $i->email,
                        $i->telefone,
                        $i->instituicao,
                        $i->categoria_label,
                        $i->is_docente_ispm ? 'Sim' : 'Não',
                        $i->email_institucional,
                        $i->modalidade_label,
                        implode(' | ', $i->mini_cursos_list),
                        $i->mini_cursos_count,
                        $i->valor_kz,
                        $i->valor_pago_informado,
                        $i->referencia_pagamento,
                        $i->validacao_pagamento_label,
                        ucfirst($i->estado),
                        $i->comprovativo_path ? asset('storage/' . $i->comprovativo_path) : '',
                        $i->observacoes,
                    ], ';');
                }
            });

            fclose($out);
        }, $filename, $headers);
    }

    public function exportPdf(Request $request): Response
    {
        $inscricoes = $this->filtered($request)->get();

        $filtros = [
            'estado'    => $request->string('estado')->toString() ?: null,
            'categoria' => $request->string('categoria')->toString() ?: null,
            'q'         => $request->string('q')->toString() ?: null,
        ];

        $pdf = Pdf::loadView('admin.inscricoes.export-pdf', [
            'inscricoes'  => $inscricoes,
            'filtros'     => $filtros,
            'categorias'  => Inscricao::CATEGORIAS,
            'totalValor'  => $inscricoes->where('estado', 'confirmada')->sum('valor_kz'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('inscricoes-' . now()->format('Y-m-d_His') . '.pdf');
    }

    private function filtered(Request $request): Builder
    {
        $query = Inscricao::query()->orderBy('nome');

        if ($request->filled('estado')) {
            $query->where('estado', $request->string('estado'));
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->string('categoria'));
        }
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($x) use ($q) {
                $x->where('nome', 'like', "%$q%")
                  ->orWhere('email', 'like', "%$q%")
                  ->orWhere('telefone', 'like', "%$q%");
            });
        }

        return $query;
    }
}
