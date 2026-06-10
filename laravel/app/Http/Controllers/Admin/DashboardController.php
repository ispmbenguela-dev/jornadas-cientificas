<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use App\Models\Inscricao;
use App\Models\Submissao;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $edicaoActual = Edicao::query()->where('status', 'actual')->first();
        $edicaoId = $request->integer('edicao_id') ?: $edicaoActual?->id;
        $edicao = $edicaoId ? Edicao::find($edicaoId) : null;

        $inscricoesQ = Inscricao::query();
        $submissoesQ = Submissao::query();
        if ($edicao) {
            $inscricoesQ->where('edicao_id', $edicao->id);
            $submissoesQ->where('edicao_id', $edicao->id);
        }

        $stats = [
            'inscricoes_total'       => (clone $inscricoesQ)->count(),
            'inscricoes_pendentes'   => (clone $inscricoesQ)->where('estado', 'pendente')->count(),
            'inscricoes_confirmadas' => (clone $inscricoesQ)->where('estado', 'confirmada')->count(),
            'submissoes_total'       => (clone $submissoesQ)->count(),
            'submissoes_pendentes'   => (clone $submissoesQ)->where('estado', 'pendente')->count(),
            'submissoes_admitidas'   => (clone $submissoesQ)->where('estado', 'admitida')->count(),
            'receita_kz'             => (int) (clone $inscricoesQ)->where('estado', 'confirmada')->sum('valor_kz'),
            'receita_pendente_kz'    => (int) (clone $inscricoesQ)->where('estado', 'pendente')->sum('valor_kz'),
            'receita_total_kz'       => (int) (clone $inscricoesQ)->whereIn('estado', ['pendente', 'confirmada'])->sum('valor_kz'),
        ];

        return view('admin.dashboard', [
            'stats'                  => $stats,
            'ultimasInscricoes'      => (clone $inscricoesQ)->latest()->limit(5)->get(),
            'ultimasSubmissoes'      => (clone $submissoesQ)->latest()->limit(5)->get(),
            'inscricoesConfirmadas'  => (clone $inscricoesQ)->where('estado', 'confirmada')->latest()->get(),
            'edicaoActual'           => $edicaoActual,
            'edicaoFiltro'           => $edicao,
            'edicoes'                => Edicao::orderByDesc('data_inicio')->get(),
        ]);
    }
}
