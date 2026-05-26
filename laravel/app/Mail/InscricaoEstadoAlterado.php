<?php

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InscricaoEstadoAlterado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inscricao $inscricao, public string $estadoAnterior)
    {
    }

    public function envelope(): Envelope
    {
        $numero = str_pad($this->inscricao->id, 4, '0', STR_PAD_LEFT);

        $assunto = match ($this->inscricao->estado) {
            'confirmada' => "Inscrição #{$numero} confirmada — XI Jornada ISPM",
            'rejeitada'  => "Inscrição #{$numero} rejeitada — XI Jornada ISPM",
            'pendente'   => "Inscrição #{$numero} em análise — XI Jornada ISPM",
            default      => "Inscrição #{$numero} actualizada — XI Jornada ISPM",
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
            view: 'emails.inscricao-estado',
            with: [
                'inscricao'      => $this->inscricao,
                'estadoAnterior' => $this->estadoAnterior,
            ],
        );
    }
}