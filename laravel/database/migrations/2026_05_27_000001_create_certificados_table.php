<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 24)->unique();
            $table->foreignId('inscricao_id')->nullable()->constrained('inscricoes')->nullOnDelete();
            $table->foreignId('submissao_id')->nullable()->constrained('submissoes')->nullOnDelete();
            $table->enum('tipo', [
                'participante',
                'prelector_mini_curso',
                'prelector_comunicacao',
                'moderador',
                'organizador',
            ]);
            $table->string('nome', 200);
            $table->string('tema', 300)->nullable();
            $table->string('mini_curso_key', 60)->nullable();
            $table->string('papel_extra', 120)->nullable();
            $table->date('data_evento');
            $table->string('pdf_path', 255)->nullable();
            $table->enum('estado', ['emitido', 'enviado', 'descarregado'])->default('emitido');
            $table->timestamp('emitido_em')->nullable();
            $table->timestamp('enviado_em')->nullable();
            $table->timestamps();

            $table->index(['tipo', 'estado']);
            $table->index('data_evento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
};