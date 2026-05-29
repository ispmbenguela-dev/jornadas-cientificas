<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->foreignId('edicao_id')->nullable()->after('id')->constrained('edicoes')->nullOnDelete();
            $table->index(['edicao_id', 'estado']);
        });

        Schema::table('submissoes', function (Blueprint $table) {
            $table->foreignId('edicao_id')->nullable()->after('id')->constrained('edicoes')->nullOnDelete();
            $table->index(['edicao_id', 'estado']);
        });

        Schema::table('certificados', function (Blueprint $table) {
            $table->foreignId('edicao_id')->nullable()->after('id')->constrained('edicoes')->nullOnDelete();
            $table->index(['edicao_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->dropForeign(['edicao_id']);
            $table->dropIndex(['edicao_id', 'estado']);
            $table->dropColumn('edicao_id');
        });

        Schema::table('submissoes', function (Blueprint $table) {
            $table->dropForeign(['edicao_id']);
            $table->dropIndex(['edicao_id', 'estado']);
            $table->dropColumn('edicao_id');
        });

        Schema::table('certificados', function (Blueprint $table) {
            $table->dropForeign(['edicao_id']);
            $table->dropIndex(['edicao_id', 'tipo']);
            $table->dropColumn('edicao_id');
        });
    }
};
