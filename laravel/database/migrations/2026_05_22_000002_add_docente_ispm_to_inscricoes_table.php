<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->string('email_institucional', 160)->nullable()->after('email');
            $table->boolean('is_docente_ispm')->default(false)->after('email_institucional');
            $table->json('verificacao_sigam')->nullable()->after('is_docente_ispm');
            $table->unsignedInteger('valor_pago_informado')->nullable()->after('valor_kz');
            $table->string('referencia_pagamento', 80)->nullable()->after('valor_pago_informado');
            $table->enum('validacao_pagamento', ['nao_aplicavel', 'pendente', 'ok', 'divergente'])
                ->default('nao_aplicavel')
                ->after('referencia_pagamento');
        });
    }

    public function down(): void
    {
        Schema::table('inscricoes', function (Blueprint $table) {
            $table->dropColumn([
                'email_institucional',
                'is_docente_ispm',
                'verificacao_sigam',
                'valor_pago_informado',
                'referencia_pagamento',
                'validacao_pagamento',
            ]);
        });
    }
};
