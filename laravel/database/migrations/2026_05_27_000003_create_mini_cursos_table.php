<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mini_cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edicao_id')->constrained('edicoes')->cascadeOnDelete();
            $table->string('chave', 80);
            $table->string('dia_label', 80)->comment('Ex: 1.º Dia · 11 Jun');
            $table->string('hora', 20)->comment('Ex: 14h00');
            $table->string('local', 80)->comment('Ex: Sala de Conferência');
            $table->string('tema', 120)->comment('Ex: IA Generativa');
            $table->string('titulo', 400);
            $table->string('prelector', 300)->nullable();
            $table->string('moderador', 200)->nullable();
            $table->unsignedSmallInteger('ordem')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['edicao_id', 'chave']);
            $table->index(['edicao_id', 'ordem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mini_cursos');
    }
};
