<?php

namespace App\Mail;

use App\Models\Certificado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificadoEmitido extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Certificado $certificado, public string $certificatePath)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address', 'vp.cientifica@ispmaravilha.com'),
                'XI Jornada Científico-Metodológica · ISPM'
            ),
            replyTo: [new Address('vp.cientifica@ispmaravilha.com', 'Comissão Científica · ISPM')],
            subject: 'O seu certificado · ' . $this->certificado->tipo_label . ' — XI Jornada ISPM',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.certificado-emitido',
            with: ['certificado' => $this->certificado],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->certificatePath)
                ->as($this->attachmentName())
                ->withMime($this->attachmentMimeType()),
        ];
    }

    private function attachmentName(): string
    {
        $extension = pathinfo($this->certificatePath, PATHINFO_EXTENSION) ?: 'pdf';

        return 'certificado-' . $this->certificado->codigo . '.' . $extension;
    }

    private function attachmentMimeType(): string
    {
        return mime_content_type($this->certificatePath) ?: 'application/octet-stream';
    }
}
