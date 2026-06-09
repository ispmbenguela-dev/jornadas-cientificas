<?php

namespace App\Http\Controllers;

use App\Models\Edicao;
use App\Models\Submissao;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();

        return view('home', [
            'edicao'          => $edicao,
            'submissaoPrazo'  => Submissao::prazoFinal(),
            'submissaoAberta' => Submissao::aberta(),
            'edicoesAnteriores' => Edicao::where('status', 'passada')
                ->where('mostrar_no_arquivo', true)
                ->orderByDesc('data_inicio')
                ->get(),
        ]);
    }

    public function programa(): View
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();
        $miniCursos = $edicao
            ? $edicao->miniCursos()->where('activo', true)->orderBy('ordem')->get()->groupBy('dia_label')
            : collect();

        return view('programa', compact('edicao', 'miniCursos'));
    }

    public function edicoes(): View
    {
        return view('edicoes.index', [
            'edicoes' => Edicao::query()
                ->where('mostrar_no_arquivo', true)
                ->orderByDesc('data_inicio')
                ->get(),
        ]);
    }

    public function edicao(string $slug): View
    {
        $edicao = Edicao::query()
            ->where('slug', $slug)
            ->where('mostrar_no_arquivo', true)
            ->firstOrFail();

        return view('edicoes.show', [
            'edicao'     => $edicao,
            'miniCursos' => $edicao->miniCursos()->where('activo', true)->orderBy('ordem')->get(),
        ]);
    }
}
