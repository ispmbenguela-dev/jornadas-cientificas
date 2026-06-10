<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $edicoes = DB::table('edicoes')->whereNotNull('taxas')->get();

        foreach ($edicoes as $edicao) {
            $taxas = json_decode($edicao->taxas, true);
            if (!is_array($taxas)) continue;

            $taxas['pta'] = ['participacao' => 2000, 'mini_curso' => 3000];

            DB::table('edicoes')
                ->where('id', $edicao->id)
                ->update(['taxas' => json_encode($taxas)]);
        }
    }

    public function down(): void
    {
        $edicoes = DB::table('edicoes')->whereNotNull('taxas')->get();

        foreach ($edicoes as $edicao) {
            $taxas = json_decode($edicao->taxas, true);
            if (!is_array($taxas)) continue;

            $taxas['pta'] = ['participacao' => 10000, 'mini_curso' => 5000];

            DB::table('edicoes')
                ->where('id', $edicao->id)
                ->update(['taxas' => json_encode($taxas)]);
        }
    }
};
