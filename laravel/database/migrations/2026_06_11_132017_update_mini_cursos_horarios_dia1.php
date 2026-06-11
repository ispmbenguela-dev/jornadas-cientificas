<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $updates = [
            'dia1_11h_ia_inclusao'    => ['hora' => '13h30', 'moderador' => 'Kelson Chavonga, MSc.'],
            'dia1_11h_seguranca'      => ['hora' => '13h30', 'moderador' => 'Luís Gomes, Lic.'],
            'dia1_14h_ia_generativa'  => ['hora' => '15h00', 'moderador' => 'Domingos Barbante, MSc.'],
            'dia1_14h_capital_humano' => ['hora' => '15h00', 'moderador' => 'Eunice Pedro, Lic.'],
            'dia1_15h_projectos'      => ['hora' => '16h00', 'moderador' => 'Kelson Chavonga, MSc.'],
        ];

        foreach ($updates as $chave => $dados) {
            DB::table('mini_cursos')
                ->where('chave', $chave)
                ->update($dados);
        }
    }

    public function down(): void
    {
        $originals = [
            'dia1_11h_ia_inclusao'    => ['hora' => '11h00', 'moderador' => 'José Filipe, MSc.'],
            'dia1_11h_seguranca'      => ['hora' => '11h00', 'moderador' => 'Luis Gomes, Lic.'],
            'dia1_14h_ia_generativa'  => ['hora' => '14h00', 'moderador' => 'José Filipe, MSc.'],
            'dia1_14h_capital_humano' => ['hora' => '14h00', 'moderador' => 'Eunice Pedro, Lic.'],
            'dia1_15h_projectos'      => ['hora' => '15h30', 'moderador' => 'José Filipe, MSc.'],
        ];

        foreach ($originals as $chave => $dados) {
            DB::table('mini_cursos')
                ->where('chave', $chave)
                ->update($dados);
        }
    }
};
