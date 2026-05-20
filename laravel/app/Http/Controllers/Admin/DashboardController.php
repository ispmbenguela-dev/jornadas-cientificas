<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscricao;
use App\Models\Submissao;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'inscricoes_total'      => Inscricao::count(),
            'inscricoes_pendentes'  => Inscricao::where('estado', 'pendente')->count(),
            'inscricoes_confirmadas' => Inscricao::where('estado', 'confirmada')->count(),
            'submissoes_total'      => Submissao::count(),
            'submissoes_pendentes'  => Submissao::where('estado', 'pendente')->count(),
            'submissoes_admitidas'  => Submissao::where('estado', 'admitida')->count(),
            'receita_kz'            => Inscricao::where('estado', 'confirmada')->sum('valor_kz'),
        ];

        return view('admin.dashboard', [
            'stats'              => $stats,
            'ultimasInscricoes'  => Inscricao::latest()->limit(5)->get(),
            'ultimasSubmissoes'  => Submissao::latest()->limit(5)->get(),
        ]);
    }
}
