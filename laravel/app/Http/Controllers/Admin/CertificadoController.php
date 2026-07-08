<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\CertificadoEmitido;
use App\Models\Certificado;
use App\Models\Inscricao;
use App\Models\Submissao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CertificadoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Certificado::query()->latest();

        if ($tipo = $request->string('tipo')->toString()) {
            $query->where('tipo', $tipo);
        }
        if ($estado = $request->string('estado')->toString()) {
            $query->where('estado', $estado);
        }
        if ($q = $request->string('q')->toString()) {
            $query->where(function ($qb) use ($q) {
                $qb->where('nome', 'like', "%{$q}%")
                   ->orWhere('codigo', 'like', "%{$q}%")
                   ->orWhere('tema', 'like', "%{$q}%");
            });
        }

        $stats = [
            'total'     => Certificado::count(),
            'emitidos'  => Certificado::where('estado', 'emitido')->count(),
            'enviados'  => Certificado::where('estado', 'enviado')->count(),
            'inscricoes_confirmadas' => Inscricao::where('estado', 'confirmada')->count(),
            'inscricoes_com_certificado' => Certificado::where('tipo', 'participante')->whereNotNull('inscricao_id')->distinct('inscricao_id')->count('inscricao_id'),
            'submissoes_admitidas' => Submissao::where('estado', 'admitida')->count(),
            'submissoes_com_certificado' => Certificado::where('tipo', 'prelector_comunicacao')->whereNotNull('submissao_id')->distinct('submissao_id')->count('submissao_id'),
        ];

        return view('admin.certificados.index', [
            'certificados' => $query->paginate(20)->withQueryString(),
            'tipos'        => Certificado::TIPOS,
            'stats'        => $stats,
        ]);
    }

    public function create(): View
    {
        return view('admin.certificados.create', [
            'tipos' => Certificado::TIPOS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tipo'        => ['required', 'in:' . implode(',', array_keys(Certificado::TIPOS))],
            'nome'        => ['required', 'string', 'max:200'],
            'tema'        => ['nullable', 'string', 'max:300'],
            'papel_extra' => ['nullable', 'string', 'max:120'],
            'data_evento' => ['required', 'date'],
        ]);

        $certificado = Certificado::create($data);
        $this->gerarPdf($certificado);

        return redirect()->route('admin.certificados.show', $certificado)
            ->with('success', 'Certificado emitido. Código: ' . $certificado->codigo);
    }

    /**
     * Gera certificados de participante automaticamente para todas as inscrições
     * confirmadas que ainda não tenham certificado.
     */
    public function gerarLote(Request $request): RedirectResponse
    {
        $request->validate([
            'data_evento' => ['required', 'date'],
        ]);

        $inscricoes = Inscricao::where('estado', 'confirmada')
            ->whereDoesntHave('certificadoParticipante')
            ->get();

        $count = 0;
        foreach ($inscricoes as $i) {
            $cert = Certificado::create([
                'tipo'         => 'participante',
                'inscricao_id' => $i->id,
                'nome'         => $i->nome,
                'data_evento'  => $request->date('data_evento'),
            ]);
            $this->gerarPdf($cert);
            $count++;
        }

        return redirect()->route('admin.certificados.index')
            ->with('success', "{$count} certificado(s) de participante emitido(s).");
    }

    /**
     * Gera certificados de prelector de comunicação livre para submissões admitidas
     * que ainda não tenham certificado.
     */
    public function gerarPrelectores(Request $request): RedirectResponse
    {
        $request->validate([
            'data_evento' => ['required', 'date'],
        ]);

        $submissoes = Submissao::where('estado', 'admitida')
            ->whereDoesntHave('certificadoPrelector')
            ->get();

        $count = 0;
        foreach ($submissoes as $s) {
            $cert = Certificado::create([
                'tipo'         => 'prelector_comunicacao',
                'submissao_id' => $s->id,
                'nome'         => $s->autor_principal,
                'tema'         => $s->titulo,
                'data_evento'  => $request->date('data_evento'),
            ]);
            $this->gerarPdf($cert);
            $count++;
        }

        return redirect()->route('admin.certificados.index')
            ->with('success', "{$count} certificado(s) de prelector emitido(s) a partir de submissões admitidas.");
    }

    /**
     * Envia em lote os certificados ainda não enviados (estado = emitido) por e-mail.
     */
    public function enviarTodos(Request $request): RedirectResponse
    {
        $query = Certificado::where('estado', 'emitido');
        if ($tipo = $request->string('tipo')->toString()) {
            $query->where('tipo', $tipo);
        }

        $enviados = 0;
        $falhas   = 0;
        $semEmail = 0;

        foreach ($query->get() as $cert) {
            $email = $cert->email_destino;
            if (!$email) {
                $semEmail++;
                continue;
            }

            try {
                $path = $this->garantirPdf($cert);
                Mail::to($email)->send(new CertificadoEmitido($cert, Storage::disk('public')->path($path)));
                $cert->update(['estado' => 'enviado', 'enviado_em' => now()]);
                $enviados++;
            } catch (\Throwable $e) {
                $falhas++;
                Log::warning('Falha no envio em lote de certificado', [
                    'certificado_id' => $cert->id,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        $msg = "{$enviados} certificado(s) enviado(s).";
        if ($semEmail > 0) $msg .= " {$semEmail} sem e-mail.";
        if ($falhas > 0)   $msg .= " {$falhas} falha(s) — ver log.";

        return redirect()->route('admin.certificados.index')->with('success', $msg);
    }

    public function buscarInscritos(Request $request): JsonResponse
    {
        $q = $request->string('q')->toString();

        $query = Inscricao::where('estado', 'confirmada')
            ->whereNotNull('email')
            ->with('certificadoParticipante');

        if (mb_strlen($q) >= 2) {
            $query->where(function ($qb) use ($q) {
                $qb->where('nome', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $inscritos = $query->orderBy('nome')->limit(30)->get();

        return response()->json($inscritos->map(fn(Inscricao $i) => [
            'id'          => $i->id,
            'nome'        => $i->nome,
            'email'       => $i->email,
            'tem_cert'    => $i->certificadoParticipante !== null,
            'cert_estado' => $i->certificadoParticipante?->estado,
        ]));
    }

    public function enviarInscritos(Request $request): RedirectResponse
    {
        $request->validate([
            'inscricao_ids'   => ['required', 'array', 'min:1'],
            'inscricao_ids.*' => ['integer', 'exists:inscricoes,id'],
            'data_evento'     => ['required', 'date'],
            'certificado_pdf' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg,webp,gif,bmp', 'max:10240'],
        ]);

        $uploadedFile = $request->file('certificado_pdf');
        $uploadedContent = $uploadedFile
            ? file_get_contents($uploadedFile->getRealPath())
            : null;
        $uploadedExtension = $uploadedFile
            ? strtolower($uploadedFile->extension() ?: $uploadedFile->getClientOriginalExtension() ?: 'pdf')
            : null;

        $enviados  = 0;
        $gerados   = 0;
        $carregados = 0;
        $falhas    = 0;
        $semEmail  = 0;

        foreach ($request->input('inscricao_ids') as $id) {
            $inscricao = Inscricao::with('certificadoParticipante')->find($id);

            if (!$inscricao?->email) {
                $semEmail++;
                continue;
            }

            $cert = $inscricao->certificadoParticipante;

            if (!$cert) {
                $cert = Certificado::create([
                    'tipo'         => 'participante',
                    'inscricao_id' => $inscricao->id,
                    'nome'         => $inscricao->nome,
                    'data_evento'  => $request->date('data_evento'),
                ]);
                $gerados++;
            }

            if ($uploadedContent !== null) {
                $filename = 'certificados/' . $cert->codigo . '.' . $uploadedExtension;
                if ($cert->pdf_path && $cert->pdf_path !== $filename && Storage::disk('public')->exists($cert->pdf_path)) {
                    Storage::disk('public')->delete($cert->pdf_path);
                }
                Storage::disk('public')->put($filename, $uploadedContent);
                $cert->update(['pdf_path' => $filename]);
                $carregados++;
            }

            try {
                $path = $this->garantirPdf($cert);
                Mail::to($inscricao->email)->send(new CertificadoEmitido($cert, Storage::disk('public')->path($path)));
                $cert->update(['estado' => 'enviado', 'enviado_em' => now()]);
                $enviados++;
            } catch (\Throwable $e) {
                $falhas++;
                Log::warning('Falha no envio de certificado por inscrito', [
                    'inscricao_id'   => $id,
                    'certificado_id' => $cert->id,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        $msg = "{$enviados} certificado(s) enviado(s) por e-mail.";
        if ($carregados > 0) $msg .= " PDF carregado aplicado a {$carregados}.";
        if ($gerados > 0)    $msg .= " {$gerados} gerado(s) automaticamente.";
        if ($semEmail > 0)   $msg .= " {$semEmail} sem e-mail associado.";
        if ($falhas > 0)     $msg .= " {$falhas} falha(s) — ver log.";

        return redirect()->route('admin.certificados.index')->with('success', $msg);
    }

    public function show(Certificado $certificado): View
    {
        return view('admin.certificados.show', compact('certificado'));
    }

    public function download(Certificado $certificado): Response
    {
        $path = $this->garantirPdf($certificado);

        $absolutePath = Storage::disk('public')->path($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf';
        $mimeType = mime_content_type($absolutePath) ?: 'application/octet-stream';

        return response()->download($absolutePath, 'certificado-' . $certificado->codigo . '.' . $extension, [
            'Content-Type' => $mimeType,
        ]);
    }

    public function preview(Certificado $certificado): Response
    {
        $pdf = Pdf::loadView('certificados.pdf', compact('certificado'))->setPaper('a4', 'landscape');
        return $pdf->stream('certificado-' . $certificado->codigo . '.pdf');
    }

    public function enviar(Certificado $certificado): RedirectResponse
    {
        $email = $certificado->email_destino;
        if (!$email) {
            return back()->with('error', 'Este certificado não tem e-mail associado. Edite e adicione manualmente.');
        }

        $path = $this->garantirPdf($certificado);

        try {
            Mail::to($email)->send(new CertificadoEmitido($certificado, Storage::disk('public')->path($path)));
            $certificado->update(['estado' => 'enviado', 'enviado_em' => now()]);
            return back()->with('success', 'Certificado enviado para ' . $email);
        } catch (\Throwable $e) {
            Log::warning('Falha a enviar certificado por e-mail', [
                'certificado_id' => $certificado->id,
                'email'          => $email,
                'error'          => $e->getMessage(),
            ]);
            return back()->with('error', 'Falhou o envio do e-mail: ' . $e->getMessage());
        }
    }

    public function destroy(Certificado $certificado): RedirectResponse
    {
        if ($certificado->pdf_path && Storage::disk('public')->exists($certificado->pdf_path)) {
            Storage::disk('public')->delete($certificado->pdf_path);
        }
        $certificado->delete();
        return redirect()->route('admin.certificados.index')->with('success', 'Certificado removido.');
    }

    private function gerarPdf(Certificado $certificado): string
    {
        $pdf = Pdf::loadView('certificados.pdf', ['certificado' => $certificado])
            ->setPaper('a4', 'landscape');

        $filename = 'certificados/' . $certificado->codigo . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());

        $certificado->update(['pdf_path' => $filename]);

        return $filename;
    }

    private function garantirPdf(Certificado $certificado): string
    {
        if ($certificado->pdf_path && Storage::disk('public')->exists($certificado->pdf_path)) {
            return $certificado->pdf_path;
        }
        return $this->gerarPdf($certificado);
    }
}
