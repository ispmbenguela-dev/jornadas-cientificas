<?php

namespace App\Http\Controllers;

use App\Models\Edicao;
use App\Models\Submissao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissaoController extends Controller
{
    public function create(): View
    {
        if (!Submissao::aberta()) {
            return view('submissao.encerrada', [
                'prazoFinal' => Submissao::prazoFinal(),
            ]);
        }

        return view('submissao.create', [
            'areas'      => Submissao::AREAS,
            'prazoFinal' => Submissao::prazoFinal(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Submissao::aberta()) {
            return redirect()->route('submissao.create')
                ->with('error', 'As submissões já encerraram.');
        }

        $data = $request->validate([
            'titulo'          => ['required', 'string', 'max:255'],
            'autor_principal' => ['required', 'string', 'max:160'],
            'coautores'       => ['nullable', 'string', 'max:1000'],
            'email'           => ['required', 'email', 'max:160'],
            'telefone'        => ['nullable', 'string', 'max:40'],
            'instituicao'     => ['required', 'string', 'max:200'],
            'area_tematica'   => ['nullable', 'string', 'max:120'],
            'resumo'          => ['required', 'string', 'max:2500'],
            'ficheiro'        => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $file = $request->file('ficheiro');
        $data['ficheiro_original'] = $file->getClientOriginalName();
        $data['ficheiro_path']     = $file->store('submissoes', 'public');
        $data['edicao_id']         = optional(Edicao::query()->where('status', 'actual')->first())->id;

        $submissao = Submissao::create($data);

        return redirect()
            ->route('submissao.sucesso', $submissao)
            ->with('success', 'Trabalho submetido com sucesso.');
    }

    public function sucesso(Submissao $submissao): View
    {
        return view('submissao.sucesso', compact('submissao'));
    }
}
