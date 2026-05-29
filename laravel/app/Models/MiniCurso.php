<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiniCurso extends Model
{
    protected $table = 'mini_cursos';

    protected $fillable = [
        'edicao_id',
        'chave',
        'dia_label',
        'hora',
        'local',
        'tema',
        'titulo',
        'prelector',
        'moderador',
        'ordem',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class);
    }

    public function toCardArray(): array
    {
        return [
            'dia'       => $this->dia_label,
            'hora'      => $this->hora,
            'local'     => $this->local,
            'tema'      => $this->tema,
            'titulo'    => $this->titulo,
            'prelector' => $this->prelector,
            'moderador' => $this->moderador,
        ];
    }
}
