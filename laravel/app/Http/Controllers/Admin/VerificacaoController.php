<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscricao;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificacaoController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim($request->string('q'));
        $resultados = collect();

        if ($q !== '') {
            $resultados = Inscricao::query()
                ->where(function ($x) use ($q) {
                    $x->where('nome', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('telefone', 'like', "%{$q}%");
                })
                ->latest()
                ->get();
        }

        return view('admin.verificacao.index', compact('resultados', 'q'));
    }
}
