<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avaliacao;
use App\Models\Edicao;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AvaliacaoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Avaliacao::query()->with('edicao')->latest();

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->string('categoria'));
        }
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->string('sexo'));
        }
        if ($request->filled('edicao_id')) {
            $query->where('edicao_id', $request->integer('edicao_id'));
        }

        $statsQuery = clone $query;
        $avaliacoes = $query->paginate(20)->withQueryString();
        $stats      = $this->calcularStats($statsQuery);

        return view('admin.avaliacoes.index', [
            'avaliacoes' => $avaliacoes,
            'stats'      => $stats,
            'edicoes'    => Edicao::query()->orderByDesc('ano')->get(),
            'categorias' => Avaliacao::CATEGORIAS,
            'secoes'     => Avaliacao::SECOES,
        ]);
    }

    public function show(Avaliacao $avaliacao): View
    {
        return view('admin.avaliacoes.show', [
            'avaliacao' => $avaliacao,
            'secoes'    => Avaliacao::SECOES,
            'likert'    => Avaliacao::LIKERT,
        ]);
    }

    public function qrcode(): Response
    {
        $url = route('avaliacao.create');

        $qr = new QrCode(
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 20,
        );

        $result = (new PngWriter())->write($qr);

        return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType());
    }

    public function qrcodePage(): View
    {
        return view('admin.avaliacoes.qrcode', [
            'url' => route('avaliacao.create'),
        ]);
    }

    public function destroy(Avaliacao $avaliacao): \Illuminate\Http\RedirectResponse
    {
        $avaliacao->delete();
        return redirect()->route('admin.avaliacoes.index')->with('success', 'Resposta removida.');
    }

    private function calcularStats(\Illuminate\Database\Eloquent\Builder $baseQuery): array
    {
        $total = (clone $baseQuery)->count();
        if (!$total) return ['total' => 0, 'media_global' => null, 'secoes' => []];

        $allQs = [];
        foreach (Avaliacao::SECOES as $secao) {
            foreach (array_keys($secao['perguntas']) as $q) {
                $allQs[] = $q;
            }
        }

        $sums  = array_fill_keys($allQs, 0);
        $rows  = (clone $baseQuery)->selectRaw(implode(', ', array_map(fn($q) => "SUM($q) as sum_$q", $allQs)))->first();

        foreach ($allQs as $q) {
            $sums[$q] = (float) ($rows->{"sum_$q"} ?? 0);
        }

        $secaoStats = [];
        foreach (Avaliacao::SECOES as $key => $secao) {
            $qs  = array_keys($secao['perguntas']);
            $sum = array_sum(array_map(fn($q) => $sums[$q], $qs));
            $secaoStats[$key] = round($sum / (count($qs) * $total), 2);
        }

        $globalSum = array_sum($sums);
        $media = round($globalSum / (count($allQs) * $total), 2);

        return ['total' => $total, 'media_global' => $media, 'secoes' => $secaoStats];
    }
}