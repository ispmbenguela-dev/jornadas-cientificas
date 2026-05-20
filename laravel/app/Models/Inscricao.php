<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricoes';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'instituicao',
        'categoria',
        'modalidade',
        'valor_kz',
        'comprovativo_path',
        'estado',
        'observacoes',
    ];

    public const CATEGORIAS = [
        'docente'   => 'Docente',
        'estudante' => 'Estudante',
        'publico'   => 'Público em Geral',
    ];

    public const MODALIDADES = [
        'participacao' => 'Participação',
        'mini_curso'   => 'Mini-Curso',
    ];

    public const TABELA_PRECOS = [
        'docente'   => ['participacao' => 10000, 'mini_curso' => 5000],
        'estudante' => ['participacao' => 2000,  'mini_curso' => 3000],
        'publico'   => ['participacao' => 10000, 'mini_curso' => 5000],
    ];

    public static function calcularValor(string $categoria, string $modalidade): int
    {
        return self::TABELA_PRECOS[$categoria][$modalidade] ?? 0;
    }

    public function getCategoriaLabelAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? $this->categoria;
    }

    public function getModalidadeLabelAttribute(): string
    {
        return self::MODALIDADES[$this->modalidade] ?? $this->modalidade;
    }

    public function getValorFormatadoAttribute(): string
    {
        return number_format($this->valor_kz, 2, ',', '.') . ' Kz';
    }
}
