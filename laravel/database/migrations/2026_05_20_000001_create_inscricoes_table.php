<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscricoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('telefone', 40);
            $table->string('instituicao')->nullable();
            $table->enum('categoria', ['docente', 'estudante', 'publico']);
            $table->enum('modalidade', ['participacao', 'mini_curso']);
            $table->unsignedInteger('valor_kz');
            $table->string('comprovativo_path')->nullable();
            $table->enum('estado', ['pendente', 'confirmada', 'rejeitada'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index(['estado', 'created_at']);
            $table->index('categoria');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscricoes');
    }
};
