<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Configuracao;
use App\Models\Edicao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AvaliacaoController extends Controller
{
    public function create(): View|\Illuminate\Http\RedirectResponse
    {
        if (Configuracao::get('avaliacao_aberta') !== '1') {
            return view('avaliacao.fechada');
        }
        $edicao = Edicao::query()->where('status', 'actual')->first()
            ?? Edicao::query()->orderByDesc('data_inicio')->first();
        return view('avaliacao.create', compact('edicao'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Configuracao::get('avaliacao_aberta') !== '1') {
            return redirect()->route('avaliacao.create');
        }
        $qs = [];
        for ($i = 1; $i <= 20; $i++) {
            $qs["q{$i}"] = ['required', 'integer', 'min:1', 'max:5'];
        }

        $data = $request->validate(array_merge([
            'sexo'      => ['required', 'in:M,F'],
            'idade'     => ['required', 'integer', 'min:15', 'max:100'],
            'categoria' => ['required', 'in:' . implode(',', array_keys(Avaliacao::CATEGORIAS))],
            'aspetos_positivos' => ['nullable', 'string', 'max:2000'],
            'sugestoes'         => ['nullable', 'string', 'max:2000'],
        ], $qs));

        $edicao = Edicao::query()->where('status', 'actual')->first()
            ?? Edicao::query()->orderByDesc('data_inicio')->first();
        $data['edicao_id'] = $edicao?->id;

        $avaliacao = Avaliacao::create($data);

        return redirect()->route('avaliacao.sucesso', $avaliacao);
    }

    public function sucesso(Avaliacao $avaliacao): View
    {
        return view('avaliacao.sucesso', compact('avaliacao'));
    }
}