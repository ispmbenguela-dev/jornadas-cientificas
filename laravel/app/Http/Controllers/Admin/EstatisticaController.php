<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscricao;
use App\Models\Submissao;
use Illuminate\View\View;

class EstatisticaController extends Controller
{
    public function index(): View
    {
        $base = Inscricao::query();

        // Totais gerais
        $total       = (clone $base)->count();
        $confirmadas = (clone $base)->where('estado', 'confirmada')->count();
        $presentes   = (clone $base)->whereNotNull('checked_in_at')->count();
        $docentesIspm = (clone $base)->where('is_docente_ispm', true)->count();

        // Receita
        $receitaConfirmada = (int) (clone $base)->where('estado', 'confirmada')->sum('valor_kz');
        $receitaTotal      = (int) (clone $base)->sum('valor_kz');

        // Por sexo (null = não informado)
        $porSexo = $this->contarPorChave(
            (clone $base)->selectRaw('sexo, COUNT(*) c')->groupBy('sexo')->pluck('c', 'sexo')->all(),
            Inscricao::SEXOS,
            'Não informado'
        );

        // Por categoria
        $porCategoria = $this->contarPorChave(
            (clone $base)->selectRaw('categoria, COUNT(*) c')->groupBy('categoria')->pluck('c', 'categoria')->all(),
            Inscricao::CATEGORIAS
        );

        // Por modalidade
        $porModalidade = $this->contarPorChave(
            (clone $base)->selectRaw('modalidade, COUNT(*) c')->groupBy('modalidade')->pluck('c', 'modalidade')->all(),
            Inscricao::MODALIDADES
        );

        // Por estado
        $porEstado = $this->contarPorChave(
            (clone $base)->selectRaw('estado, COUNT(*) c')->groupBy('estado')->pluck('c', 'estado')->all(),
            ['pendente' => 'Pendente', 'confirmada' => 'Confirmada', 'rejeitada' => 'Rejeitada']
        );

        // Por mini-curso (campo JSON → conta-se em PHP)
        $catalogo = Inscricao::miniCursosParaFiltro();
        $contMc = [];
        Inscricao::whereNotNull('mini_cursos')->pluck('mini_cursos')->each(function ($arr) use (&$contMc) {
            foreach ((array) $arr as $chave) {
                $contMc[$chave] = ($contMc[$chave] ?? 0) + 1;
            }
        });
        arsort($contMc);
        $porMiniCurso = [];
        foreach ($contMc as $chave => $n) {
            $porMiniCurso[] = [
                'titulo' => $catalogo[$chave]['titulo'] ?? $chave,
                'hora'   => $catalogo[$chave]['hora'] ?? null,
                'local'  => $catalogo[$chave]['local'] ?? null,
                'total'  => $n,
            ];
        }

        // Top instituições
        $topInstituicoes = (clone $base)
            ->selectRaw('COALESCE(NULLIF(TRIM(instituicao), ""), "—") inst, COUNT(*) c')
            ->groupBy('inst')
            ->orderByDesc('c')
            ->limit(10)
            ->pluck('c', 'inst')
            ->all();

        // Submissões
        $submissoes = [
            'total'     => Submissao::count(),
            'admitidas' => Submissao::where('estado', 'admitida')->count(),
            'pendentes' => Submissao::where('estado', 'pendente')->count(),
            'rejeitadas'=> Submissao::where('estado', 'rejeitada')->count(),
        ];

        return view('admin.estatisticas.index', compact(
            'total', 'confirmadas', 'presentes', 'docentesIspm',
            'receitaConfirmada', 'receitaTotal',
            'porSexo', 'porCategoria', 'porModalidade', 'porEstado',
            'porMiniCurso', 'topInstituicoes', 'submissoes'
        ));
    }

    /**
     * Mapeia contagens (chave => n) para labels legíveis, garantindo que todas
     * as chaves do catálogo aparecem (mesmo com 0).
     */
    private function contarPorChave(array $contagens, array $labels, ?string $labelNulo = null): array
    {
        $out = [];
        foreach ($labels as $chave => $label) {
            $out[$label] = (int) ($contagens[$chave] ?? 0);
        }
        if ($labelNulo !== null) {
            $out[$labelNulo] = (int) ($contagens[''] ?? $contagens[null] ?? 0);
        }
        return $out;
    }
}