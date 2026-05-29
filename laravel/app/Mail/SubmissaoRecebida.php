<?php

namespace App\Mail;

use App\Models\Submissao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissaoRecebida extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Submissao $submissao)
    {
    }

    public function envelope(): Envelope
    {
        $numero = str_pad($this->submissao->id, 4, '0', STR_PAD_LEFT);

        return new Envelope(
            from: new Address(
                config('mail.from.address', 'vp.cientifica@ispmaravilha.com'),
                'XI Jornada Científico-Metodológica · ISPM'
            ),
            replyTo: [new Address('vp.cientifica@ispmaravilha.com', 'Comissão Científica · ISPM')],
            subject: "Submissão #{$numero} recebida — XI Jornada ISPM",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.submissao-recebida',
            with: ['submissao' => $this->submissao],
        );
    }
}
