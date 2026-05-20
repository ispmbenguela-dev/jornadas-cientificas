<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Submissao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubmissaoController extends Controller
{
    public function index(Request $request): View
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

        return view('admin.submissoes.index', [
            'submissoes' => $query->paginate(20)->withQueryString(),
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
}
