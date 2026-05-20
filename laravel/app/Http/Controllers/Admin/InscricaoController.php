<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscricao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscricaoController extends Controller
{
    public function index(Request $request): View
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

        return view('admin.inscricoes.index', [
            'inscricoes' => $query->paginate(20)->withQueryString(),
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
}
