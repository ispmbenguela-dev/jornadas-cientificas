<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edicao_id')->nullable()->constrained('edicoes')->nullOnDelete();
            $table->enum('sexo', ['M', 'F']);
            $table->unsignedTinyInteger('idade');
            $table->enum('categoria', ['estudante', 'docente', 'investigador', 'convidado']);

            // Organização do Evento
            $table->unsignedTinyInteger('q1');
            $table->unsignedTinyInteger('q2');
            $table->unsignedTinyInteger('q3');
            $table->unsignedTinyInteger('q4');

            // Conteúdo Científico
            $table->unsignedTinyInteger('q5');
            $table->unsignedTinyInteger('q6');
            $table->unsignedTinyInteger('q7');
            $table->unsignedTinyInteger('q8');

            // Palestrantes e Moderadores
            $table->unsignedTinyInteger('q9');
            $table->unsignedTinyInteger('q10');
            $table->unsignedTinyInteger('q11');

            // Infraestruturas e Recursos
            $table->unsignedTinyInteger('q12');
            $table->unsignedTinyInteger('q13');
            $table->unsignedTinyInteger('q14');

            // Impacto do Evento
            $table->unsignedTinyInteger('q15');
            $table->unsignedTinyInteger('q16');
            $table->unsignedTinyInteger('q17');
            $table->unsignedTinyInteger('q18');

            // Avaliação Global
            $table->unsignedTinyInteger('q19');
            $table->unsignedTinyInteger('q20');

            // Perguntas abertas
            $table->text('aspetos_positivos')->nullable();
            $table->text('sugestoes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
    }
};