<?php

namespace App\Http\Controllers;

use App\Models\Submissao;
use Illuminate\Http\Response;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'submissaoPrazo' => Submissao::prazoFinal(),
            'submissaoAberta' => Submissao::aberta(),
        ]);
    }

    public function programa(): Response
    {
        $path = public_path('programa.html');
        $html = file_get_contents($path);
        $html = str_replace('href="index.html"', 'href="' . route('home') . '"', $html);

        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }
}
