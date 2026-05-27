<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CertificadoController extends Controller
{
    public function verify(string $codigo): View
    {
        $certificado = Certificado::where('codigo', $codigo)->first();

        return view('certificados.verify', [
            'codigo'      => $codigo,
            'certificado' => $certificado,
        ]);
    }

    public function download(string $codigo): Response
    {
        $certificado = Certificado::where('codigo', $codigo)->firstOrFail();

        if (!$certificado->pdf_path || !Storage::disk('public')->exists($certificado->pdf_path)) {
            abort(404);
        }

        if ($certificado->estado === 'emitido') {
            $certificado->update(['estado' => 'descarregado']);
        }

        return response()->download(
            Storage::disk('public')->path($certificado->pdf_path),
            'certificado-' . $certificado->codigo . '.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}