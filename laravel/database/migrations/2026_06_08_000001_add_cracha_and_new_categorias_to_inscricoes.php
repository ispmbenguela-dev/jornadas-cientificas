<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Alarga o enum de categoria para incluir as novas categorias
        DB::statement("ALTER TABLE inscricoes MODIFY categoria ENUM('docente','estudante','publico','pta','mco') NOT NULL");

        Schema::table('inscricoes', function (Blueprint $table) {
            $table->string('cracha_path')->nullable()->after('comprovativo_path');
        });
    }

    public function down(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->dropColumn('cracha_path');
        });

        DB::statement("ALTER TABLE inscricoes MODIFY categoria ENUM('docente','estudante','publico') NOT NULL");
    }
};
