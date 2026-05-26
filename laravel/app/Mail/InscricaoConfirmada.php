<?php

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InscricaoConfirmada extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inscricao $inscricao)
    {
    }

    public function envelope(): Envelope
    {
        $numero = str_pad($this->inscricao->id, 4, '0', STR_PAD_LEFT);

        return new Envelope(
            from: new Address(
                config('mail.from.address', 'vp.cientifica@ispmaravilha.com'),
                'XI Jornada Científico-Metodológica · ISPM'
            ),
            replyTo: [new Address('vp.cientifica@ispmaravilha.com', 'Comissão Científica · ISPM')],
            subject: "Inscrição #{$numero} recebida — XI Jornada ISPM",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inscricao-confirmada',
            with: ['inscricao' => $this->inscricao],
        );
    }
}