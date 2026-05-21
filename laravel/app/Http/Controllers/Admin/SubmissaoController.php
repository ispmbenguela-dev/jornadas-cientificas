<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submissao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class SubmissaoController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.submissoes.index', [
            'submissoes' => $this->filtered($request)->paginate(20)->withQueryString(),
        ]);
    }

    public function show(Submissao $submissao): View
    {
        return view('admin.submissoes.show', compact('submissao'));
    }

    public function update(Request $request, Submissao $submissao): RedirectResponse
    {
        $data = $request->validate([
            'estado'  => ['required', 'in:pendente,admitida,rejeitada'],
            'parecer' => ['nullable', 'string', 'max:2000'],
        ]);

        $submissao->update($data);

        return back()->with('success', 'Submissão actualizada.');
    }

    public function destroy(Submissao $submissao): RedirectResponse
    {
        $submissao->delete();
        return redirect()->route('admin.submissoes.index')->with('success', 'Submissão removida.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'submissoes-' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->streamDownload(function () use ($request) {
            $out = fopen('php://output', 'w');

            // BOM UTF-8 para o Excel abrir acentos correctamente
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID', 'Submetido em', 'Título', 'Autor Principal', 'Coautores',
                'Email', 'Telefone', 'Instituição', 'Área Temática',
                'Resumo', 'Ficheiro Original', 'Ficheiro (URL)', 'Estado', 'Parecer',
            ], ';');

            $this->filtered($request)->chunk(500, function ($linha) use ($out) {
                foreach ($linha as $s) {
                    fputcsv($out, [
                        $s->id,
                        optional($s->created_at)->format('d/m/Y H:i'),
                        $s->titulo,
                        $s->autor_principal,
                        $s->coautores,
                        $s->email,
                        $s->telefone,
                        $s->instituicao,
                        $s->area_tematica,
                        $s->resumo,
                        $s->ficheiro_original,
                        $s->ficheiro_path ? asset('storage/' . $s->ficheiro_path) : '',
                        ucfirst($s->estado),
                        $s->parecer,
                    ], ';');
                }
            });

            fclose($out);
        }, $filename, $headers);
    }

    public function exportPdf(Request $request): Response
    {
        $submissoes = $this->filtered($request)->get();

        $filtros = [
            'estado' => $request->string('estado')->toString() ?: null,
            'q'      => $request->string('q')->toString() ?: null,
        ];

        $pdf = Pdf::loadView('admin.submissoes.export-pdf', [
            'submissoes' => $submissoes,
            'filtros'    => $filtros,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('submissoes-' . now()->format('Y-m-d_His') . '.pdf');
    }

    private function filtered(Request $request): Builder
    {
        $query = Submissao::query()->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->string('estado'));
        }
        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($x) use ($q) {
                $x->where('titulo', 'like', "%$q%")
                  ->orWhere('autor_principal', 'like', "%$q%")
                  ->orWhere('email', 'like', "%$q%");
            });
        }

        return $query;
    }
}
