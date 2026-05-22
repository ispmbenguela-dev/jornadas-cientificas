<?php

namespace App\Http\Controllers;

use App\Models\Inscricao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InscricaoController extends Controller
{
    public function create(): View
    {
        return view('inscricao.create', [
            'categorias'  => Inscricao::CATEGORIAS,
            'modalidades' => Inscricao::MODALIDADES,
            'precos'      => Inscricao::TABELA_PRECOS,
            'miniCursos'  => Inscricao::MINI_CURSOS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nome'           => ['required', 'string', 'max:160'],
            'email'          => ['required', 'email', 'max:160'],
            'telefone'       => ['required', 'string', 'max:40'],
            'instituicao'    => ['nullable', 'string', 'max:160'],
            'categoria'      => ['required', 'in:docente,estudante,publico'],
            'modalidade'     => ['required', 'in:participacao,mini_curso'],
            'mini_cursos'    => ['nullable', 'array', 'required_if:modalidade,mini_curso'],
            'mini_cursos.*'  => ['string', 'in:' . implode(',', array_keys(Inscricao::MINI_CURSOS))],
            'comprovativo'   => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'mini_cursos.required_if' => 'Seleccione pelo menos um mini-curso.',
        ]);

        if ($data['modalidade'] !== 'mini_curso') {
            $data['mini_cursos'] = null;
            $quantidade = 1;
        } else {
            $data['mini_cursos'] = array_values(array_unique($data['mini_cursos'] ?? []));
            $quantidade = count($data['mini_cursos']);
        }

        $data['valor_kz'] = Inscricao::calcularValor($data['categoria'], $data['modalidade'], $quantidade);

        if ($request->hasFile('comprovativo')) {
            $data['comprovativo_path'] = $request->file('comprovativo')
                ->store('comprovativos', 'public');
        }

        $inscricao = Inscricao::create($data);

        return redirect()
            ->route('inscricao.sucesso', $inscricao)
            ->with('success', 'Inscrição registada com sucesso.');
    }

    public function sucesso(Inscricao $inscricao): View
    {
        return view('inscricao.sucesso', compact('inscricao'));
    }
}
