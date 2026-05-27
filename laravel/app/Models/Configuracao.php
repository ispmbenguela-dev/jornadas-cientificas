<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = ['chave', 'valor', 'tipo', 'descricao'];

    public const KEYS = [
        'logo_path'        => ['tipo' => 'image', 'descricao' => 'Logo principal — apresentado na navbar e rodapé.'],
        'simbolo_path'     => ['tipo' => 'image', 'descricao' => 'Símbolo (mosaico de 4 ícones) — usado como favicon.'],
        'banner_path'      => ['tipo' => 'image', 'descricao' => 'Banner principal — apresentado no topo da página inicial.'],
        'submissao_prazo'  => ['tipo' => 'date',  'descricao' => 'Data limite (inclusiva) para submissão de trabalhos científicos.'],
    ];

    public static function get(string $chave, $default = null)
    {
        return static::query()->where('chave', $chave)->value('valor') ?? $default;
    }

    public static function set(string $chave, ?string $valor, string $tipo = 'text'): self
    {
        return static::updateOrCreate(
            ['chave' => $chave],
            ['valor' => $valor, 'tipo' => $tipo],
        );
    }

    public static function imageUrl(string $chave): ?string
    {
        $path = static::get($chave);
        return $path ? asset('storage/' . ltrim($path, '/')) : null;
    }
}
