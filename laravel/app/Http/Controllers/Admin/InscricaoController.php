<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscricao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class InscricaoController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.inscricoes.index', [
            'inscricoes' => $this->filtered($request)->paginate(20)->withQueryString(),
            'categorias' => Inscricao::CATEGORIAS,
        ]);
    }

    public function show(Inscricao $inscricao): View
    {
        return view('admin.inscricoes.show', compact('inscricao'));
    }

    public function update(Request $request, Inscricao $inscricao): RedirectResponse
    {
        $data = $request->validate([
            'estado'      => ['required', 'in:pendente,confirmada,rejeitada'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ]);

        $inscricao->update($data);

        return back()->with('success', 'Inscrição actualizada.');
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
        $query = Inscricao::query()->latest();

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
