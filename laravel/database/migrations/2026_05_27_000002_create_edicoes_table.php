<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edicoes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_romano', 10);
            $table->unsignedSmallInteger('numero_inteiro');
            $table->string('slug', 80)->unique();
            $table->string('nome', 200);
            $table->string('nome_curto', 80)->nullable();
            $table->string('tipo', 60)->default('geral')->comment('geral, departamental, etc.');
            $table->unsignedSmallInteger('ano');
            $table->text('lema')->nullable();
            $table->text('descricao')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('local', 200)->nullable();
            $table->date('inscricao_inicio')->nullable();
            $table->date('inscricao_fim')->nullable();
            $table->date('submissao_inicio')->nullable();
            $table->date('submissao_fim')->nullable();
            $table->date('trabalhos_admitidos_inicio')->nullable();
            $table->date('trabalhos_admitidos_fim')->nullable();
            $table->string('presidente_nome', 200)->default('JOSÉ JANUÁRIO, PhD.');
            $table->string('presidente_titulo', 60)->default('O PRESIDENTE');
            $table->enum('status', ['futura', 'actual', 'passada'])->default('futura');
            $table->json('taxas')->nullable()->comment('docente/estudante/publico × participacao/mini_curso');
            $table->string('banner_path', 255)->nullable();
            $table->string('cor_primaria', 9)->default('#0f4c81');
            $table->string('cor_secundaria', 9)->default('#f37021');
            $table->boolean('mostrar_no_arquivo')->default(true);
            $table->timestamps();

            $table->index('status');
            $table->index('ano');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edicoes');
    }
};
