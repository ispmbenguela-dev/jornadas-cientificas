<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use App\Models\Inscricao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class McoController extends Controller
{
    public function index(): View
    {
        $membros = Inscricao::query()
            ->where('categoria', 'mco')
            ->latest()
            ->paginate(30);

        return view('admin.mco.index', compact('membros'));
    }

    public function create(): View
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();
        return view('admin.mco.form', [
            'membro' => new Inscricao(),
            'edicao' => $edicao,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $edicao = Edicao::query()->where('status', 'actual')->first();

        $data['categoria']            = 'mco';
        $data['modalidade']           = 'participacao';
        $data['valor_kz']             = 0;
        $data['validacao_pagamento']  = 'nao_aplicavel';
        $data['edicao_id']            = $edicao?->id;

        Inscricao::create($data);

        return redirect()->route('admin.mco.index')
            ->with('success', 'Membro da Comissão Organizadora adicionado.');
    }

    public function edit(Inscricao $mco): View
    {
        abort_unless($mco->categoria === 'mco', 404);

        return view('admin.mco.form', [
            'membro' => $mco,
            'edicao' => $mco->edicao,
        ]);
    }

    public function update(Request $request, Inscricao $mco): RedirectResponse
    {
        abort_unless($mco->categoria === 'mco', 404);

        $data = $this->validated($request);
        $mco->update($data);

        return redirect()->route('admin.mco.index')
            ->with('success', 'Membro actualizado.');
    }

    public function destroy(Inscricao $mco): RedirectResponse
    {
        abort_unless($mco->categoria === 'mco', 404);

        $mco->delete();

        return redirect()->route('admin.mco.index')
            ->with('success', 'Membro removido.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nome'         => ['required', 'string', 'max:160'],
            'email'        => ['required', 'email', 'max:160'],
            'telefone'     => ['required', 'string', 'max:40'],
            'instituicao'  => ['nullable', 'string', 'max:160'],
            'estado'       => ['required', 'in:pendente,confirmada,rejeitada'],
            'observacoes'  => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
