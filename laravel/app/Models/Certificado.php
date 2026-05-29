<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificado extends Model
{
    protected $table = 'certificados';

    protected $fillable = [
        'codigo',
        'edicao_id',
        'inscricao_id',
        'submissao_id',
        'tipo',
        'nome',
        'tema',
        'mini_curso_key',
        'papel_extra',
        'data_evento',
        'pdf_path',
        'estado',
        'emitido_em',
        'enviado_em',
    ];

    protected $casts = [
        'data_evento' => 'date',
        'emitido_em'  => 'datetime',
        'enviado_em'  => 'datetime',
    ];

    public const TIPOS = [
        'participante'          => 'Participante',
        'prelector_mini_curso'  => 'Prelector de Mini-Curso',
        'prelector_comunicacao' => 'Prelector de Comunicação Livre',
        'moderador'             => 'Moderador',
        'organizador'           => 'Organizador',
    ];

    protected static function booted(): void
    {
        static::creating(function (Certificado $c) {
            if (empty($c->codigo)) {
                $c->codigo = self::gerarCodigo();
            }
            if (empty($c->emitido_em)) {
                $c->emitido_em = now();
            }
        });
    }

    public static function gerarCodigo(): string
    {
        do {
            $codigo = 'XI-' . now()->format('y') . '-' . strtoupper(Str::random(8));
        } while (self::where('codigo', $codigo)->exists());
        return $codigo;
    }

    public function inscricao(): BelongsTo
    {
        return $this->belongsTo(Inscricao::class);
    }

    public function submissao(): BelongsTo
    {
        return $this->belongsTo(Submissao::class);
    }

    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return self::TIPOS[$this->tipo] ?? $this->tipo;
    }

    public function getEmailDestinoAttribute(): ?string
    {
        if ($this->inscricao?->email) {
            return $this->inscricao->email;
        }
        if ($this->submissao?->email) {
            return $this->submissao->email;
        }
        return null;
    }

    public function getVerifyUrlAttribute(): string
    {
        return route('certificado.verify', $this->codigo);
    }

    public function getQrDataUriAttribute(): ?string
    {
        try {
            $builder = new \Endroid\QrCode\Builder\Builder(
                writer: new \Endroid\QrCode\Writer\SvgWriter(),
                writerOptions: [],
                data: $this->verify_url,
                encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::Low,
                size: 200,
                margin: 2,
            );
            $result = $builder->build();
            return $result->getDataUri();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Constrói o texto do corpo do certificado consoante o tipo.
     */
    public function getCorpoTextoAttribute(): string
    {
        $edicao = $this->edicao ?? Edicao::query()->where('status', 'actual')->first();
        $evento = $edicao?->nome ?? 'XI Jornada Científico-Metodológica Geral';
        $instituicao = 'Instituto Superior Politécnico Maravilha';
        $datas = $edicao ? $edicao->data_extenso : 'nos dias 11 e 12 de Junho de 2026';
        $lema = $edicao?->lema ?? 'O Ensino Superior e os desafios do Desenvolvimento Económico e Social de Angola';

        return match ($this->tipo) {
            'prelector_mini_curso' => 'participou no mini-curso como prelector, com o tema: "'
                . ($this->tema ?: '—') . '", na '
                . $evento . ', promovida pelo ' . $instituicao
                . ', ' . $datas . ', com o Lema: "' . $lema . '".',
            'prelector_comunicacao' => 'ministrou com êxito, a comunicação livre, com o tema: "'
                . ($this->tema ?: '—') . '", na '
                . $evento . ' promovida pelo ' . $instituicao
                . ', ' . $datas . ', com o Lema: "' . $lema . '".',
            'moderador' => 'desempenhou a função de moderador'
                . ($this->tema ? ' do painel "' . $this->tema . '"' : '')
                . ', na ' . $evento . ' promovida pelo ' . $instituicao
                . ', ' . $datas . '.',
            'organizador' => 'integrou a comissão organizadora'
                . ($this->papel_extra ? ' como ' . $this->papel_extra : '')
                . ' da ' . $evento . ' promovida pelo ' . $instituicao
                . ', ' . $datas . '.',
            default => 'participou na ' . $evento . ', promovida pelo '
                . $instituicao . ', ' . $datas . ', com o Lema: "' . $lema . '".',
        };
    }
}