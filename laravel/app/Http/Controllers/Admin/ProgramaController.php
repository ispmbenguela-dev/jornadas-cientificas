<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use Illuminate\View\View;

class ProgramaController extends Controller
{
    public function index(): View
    {
        $edicao = Edicao::query()->where('status', 'actual')->first();
        $miniCursos = $edicao
            ? $edicao->miniCursos()->where('activo', true)->orderBy('ordem')->get()->groupBy('dia_label')
            : collect();

        return view('admin.programa.index', compact('edicao', 'miniCursos'));
    }
}
