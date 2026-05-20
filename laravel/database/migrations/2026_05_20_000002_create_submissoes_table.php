<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('autor_principal');
            $table->text('coautores')->nullable();
            $table->string('email');
            $table->string('telefone', 40)->nullable();
            $table->string('instituicao');
            $table->string('area_tematica')->nullable();
            $table->text('resumo');
            $table->string('ficheiro_path');
            $table->string('ficheiro_original');
            $table->enum('estado', ['pendente', 'admitida', 'rejeitada'])->default('pendente');
            $table->text('parecer')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissoes');
    }
};
