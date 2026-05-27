<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submissao extends Model
{
    protected $table = 'submissoes';

    protected $fillable = [
        'titulo',
        'autor_principal',
        'coautores',
        'email',
        'telefone',
        'instituicao',
        'area_tematica',
        'resumo',
        'ficheiro_path',
        'ficheiro_original',
        'estado',
        'parecer',
    ];

    public const AREAS = [
        'Ciências Económicas e Jurídicas',
        'Ciência e Tecnologia',
        'Educação e Pedagogia',
        'Saúde e Bem-Estar',
        'Engenharia e Inovação',
        'Sociais e Humanidades',
        'Outras',
    ];

    public const ESTADOS = [
        'pendente'  => 'Pendente',
        'admitida'  => 'Admitida',
        'rejeitada' => 'Rejeitada',
    ];

    public function getEstadoLabelAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }

    public function certificadoPrelector(): HasOne
    {
        return $this->hasOne(Certificado::class)->where('tipo', 'prelector_comunicacao');
    }
}
