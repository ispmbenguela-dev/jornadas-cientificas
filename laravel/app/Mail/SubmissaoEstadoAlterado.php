<?php

namespace App\Mail;

use App\Models\Submissao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissaoEstadoAlterado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Submissao $submissao, public string $estadoAnterior)
    {
    }

    public function envelope(): Envelope
    {
        $numero = str_pad($this->submissao->id, 4, '0', STR_PAD_LEFT);

        $assunto = match ($this->submissao->estado) {
            'admitida'  => "Submissão #{$numero} admitida — XI Jornada ISPM",
            'rejeitada' => "Submissão #{$numero} não admitida — XI Jornada ISPM",
            'pendente'  => "Submissão #{$numero} em análise — XI Jornada ISPM",
            default     => "Submissão #{$numero} actualizada — XI Jornada ISPM",
        };

        return new Envelope(
            from: new Address(
                config('mail.from.address', 'vp.cientifica@ispmaravilha.com'),
                'XI Jornada Científico-Metodológica · ISPM'
            ),
            replyTo: [new Address('vp.cientifica@ispmaravilha.com', 'Comissão Científica · ISPM')],
            subject: $assunto,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.submissao-estado',
            with: [
                'submissao'      => $this->submissao,
                'estadoAnterior' => $this->estadoAnterior,
            ],
        );
    }
}
