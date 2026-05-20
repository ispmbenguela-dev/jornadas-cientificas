<?php

namespace Database\Seeders;

use App\Models\Configuracao;
use Illuminate\Database\Seeder;

class ConfiguracaoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Configuracao::KEYS as $chave => $meta) {
            Configuracao::firstOrCreate(
                ['chave' => $chave],
                ['valor' => null, 'tipo' => $meta['tipo'], 'descricao' => $meta['descricao']],
            );
        }
    }
}
