<?php

use App\Models\Inscricao;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $chave = 'dia1_15h_ia_negocios';

        Inscricao::whereJsonContains('mini_cursos', $chave)->each(function (Inscricao $insc) use ($chave) {
            $cursos = array_values(array_filter(
                $insc->mini_cursos ?? [],
                fn($c) => $c !== $chave
            ));

            $qtd = count($cursos);

            // Recalcular valor: se ficou sem mini-cursos muda modalidade para participação
            if ($qtd === 0) {
                $insc->modalidade  = 'participacao';
                $insc->mini_cursos = [];
            } else {
                $insc->mini_cursos = $cursos;
            }

            $edicao = $insc->edicao;
            $insc->valor_kz = Inscricao::calcularValor(
                $insc->categoria,
                $insc->modalidade,
                $qtd,
                (bool) $insc->is_docente_ispm,
                $edicao
            );

            $insc->save();
        });
    }

    public function down(): void
    {
        // Irreversível — não é possível restaurar os mini-cursos removidos
    }
};
