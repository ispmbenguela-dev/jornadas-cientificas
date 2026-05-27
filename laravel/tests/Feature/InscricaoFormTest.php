<?php

namespace Tests\Feature;

use App\Mail\InscricaoConfirmada;
use App\Models\Inscricao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InscricaoFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    private function fakeSigam(bool $isDocente): void
    {
        Http::fake([
            'sigam.ispm.online/api/verify-role' => Http::response([
                'exists'     => true,
                'verified'   => $isDocente,
                'role'       => 'teacher',
                'message'    => $isDocente ? 'Utilizador tem este role' : 'Utilizador NÃO tem este role',
                'user_roles' => $isDocente ? [['id' => 1, 'name' => 'teacher']] : [['id' => 6, 'name' => 'student']],
                'user'       => ['id' => 13268, 'name' => 'Teste Docente', 'email' => 'teste@ispm.ao'],
            ], 200),
        ]);
    }

    private function basePayload(array $overrides = []): array
    {
        return array_merge([
            'nome'        => 'João Teste',
            'email'       => 'joao@example.com',
            'telefone'    => '923000000',
            'instituicao' => 'Universidade Exemplo',
            'categoria'   => 'estudante',
            'modalidade'  => 'participacao',
        ], $overrides);
    }

    /** Aceita inscrição sem comprovativo quando valor = 0 (gratuito docente ISPM) */
    private function withFakeComprovativoIfNeeded(array $payload, int $valorEsperado, ?int $valorPago = null): array
    {
        if ($valorEsperado > 0) {
            $payload['valor_pago_informado'] = $valorPago ?? $valorEsperado;
            $payload['referencia_pagamento'] = 'REF-TESTE-' . uniqid();
            $payload['comprovativo'] = UploadedFile::fake()->create('comprovativo.pdf', 50, 'application/pdf');
        }
        return $payload;
    }

    // =========================================================
    // PARTICIPAÇÃO (sem mini-cursos)
    // =========================================================

    public function test_estudante_participacao(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload(['categoria' => 'estudante', 'modalidade' => 'participacao']),
            2000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(2000, $i->valor_kz);
        $this->assertNull($i->mini_cursos);
        $this->assertFalse($i->is_docente_ispm);
        Mail::assertSent(InscricaoConfirmada::class);
    }

    public function test_publico_participacao(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload(['categoria' => 'publico', 'modalidade' => 'participacao']),
            10000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(10000, $i->valor_kz);
        $this->assertNull($i->mini_cursos);
    }

    public function test_docente_externo_participacao(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload(['categoria' => 'docente', 'modalidade' => 'participacao', 'instituicao' => 'UAN']),
            10000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(10000, $i->valor_kz);
        $this->assertFalse($i->is_docente_ispm);
    }

    // =========================================================
    // MINI-CURSO (single & multi)
    // =========================================================

    public function test_estudante_um_mini_curso(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'categoria'   => 'estudante',
                'modalidade'  => 'mini_curso',
                'mini_cursos' => ['dia1_14h_ia_generativa'],
            ]),
            3000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(3000, $i->valor_kz);
        $this->assertEquals(['dia1_14h_ia_generativa'], $i->mini_cursos);
        $this->assertEquals(1, $i->mini_cursos_count);
        $this->assertEquals('ok', $i->validacao_pagamento);
    }

    public function test_estudante_tres_mini_cursos(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'categoria'   => 'estudante',
                'modalidade'  => 'mini_curso',
                'mini_cursos' => ['dia1_14h_ia_generativa', 'dia1_15h_ia_negocios', 'dia2_09h_educacao_inclusiva'],
            ]),
            9000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(9000, $i->valor_kz);
        $this->assertCount(3, $i->mini_cursos);
    }

    public function test_publico_um_mini_curso(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'categoria'   => 'publico',
                'modalidade'  => 'mini_curso',
                'mini_cursos' => ['dia1_11h_ia_inclusao'],
            ]),
            5000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $this->assertEquals(5000, Inscricao::firstWhere('email', 'joao@example.com')->valor_kz);
    }

    public function test_publico_dois_mini_cursos(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'categoria'   => 'publico',
                'modalidade'  => 'mini_curso',
                'mini_cursos' => ['dia1_11h_ia_inclusao', 'dia1_14h_capital_humano'],
            ]),
            10000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $this->assertEquals(10000, Inscricao::firstWhere('email', 'joao@example.com')->valor_kz);
    }

    public function test_docente_externo_dois_mini_cursos(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'categoria'   => 'docente',
                'modalidade'  => 'mini_curso',
                'instituicao' => 'UAN',
                'mini_cursos' => ['dia1_11h_ia_inclusao', 'dia1_14h_capital_humano'],
            ]),
            10000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(10000, $i->valor_kz);
        $this->assertFalse($i->is_docente_ispm);
    }

    // =========================================================
    // DOCENTE ISPM (com SIGAM mockado)
    // =========================================================

    public function test_docente_ispm_participacao_com_1_mini_curso_bonus_gratuito(): void
    {
        $this->fakeSigam(isDocente: true);

        $payload = $this->basePayload([
            'categoria'           => 'docente',
            'modalidade'          => 'participacao',
            'instituicao'         => 'ISPM',
            'email_institucional' => 'docente@ispm.ao',
            'mini_cursos'         => ['dia1_14h_ia_generativa'],
        ]);
        // total = 0 → não envia comprovativo
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(0, $i->valor_kz);
        $this->assertTrue($i->is_docente_ispm);
        $this->assertEquals(['dia1_14h_ia_generativa'], $i->mini_cursos);
        $this->assertEquals('nao_aplicavel', $i->validacao_pagamento);
        $this->assertNull($i->comprovativo_path);
    }

    public function test_docente_ispm_mini_curso_unico_gratuito(): void
    {
        $this->fakeSigam(isDocente: true);

        $payload = $this->basePayload([
            'categoria'           => 'docente',
            'modalidade'          => 'mini_curso',
            'instituicao'         => 'Instituto Superior Politécnico Maravilha',
            'email_institucional' => 'docente@ispm.ao',
            'mini_cursos'         => ['dia2_09h_educacao_inclusiva'],
        ]);
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(0, $i->valor_kz);
        $this->assertTrue($i->is_docente_ispm);
        $this->assertEquals('nao_aplicavel', $i->validacao_pagamento);
    }

    public function test_docente_ispm_mini_curso_dois_um_grátis_um_pago(): void
    {
        $this->fakeSigam(isDocente: true);

        $payload = $this->basePayload([
            'categoria'           => 'docente',
            'modalidade'          => 'mini_curso',
            'instituicao'         => 'ISPM',
            'email_institucional' => 'docente@ispm.ao',
            'mini_cursos'         => ['dia1_11h_ia_inclusao', 'dia1_15h_projectos'],
        ]);
        // Espera-se 5.000 (apenas o 2.º paga)
        $payload = $this->withFakeComprovativoIfNeeded($payload, 5000);
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(5000, $i->valor_kz);
        $this->assertTrue($i->is_docente_ispm);
        $this->assertEquals(2, $i->mini_cursos_count);
        $this->assertEquals('ok', $i->validacao_pagamento);
    }

    public function test_docente_ispm_mini_curso_tres_dois_pagam(): void
    {
        $this->fakeSigam(isDocente: true);

        $payload = $this->basePayload([
            'categoria'           => 'docente',
            'modalidade'          => 'mini_curso',
            'instituicao'         => 'ISPM',
            'email_institucional' => 'docente@ispm.ao',
            'mini_cursos'         => ['dia1_11h_ia_inclusao', 'dia1_14h_ia_generativa', 'dia2_09h_educacao_inclusiva'],
        ]);
        $payload = $this->withFakeComprovativoIfNeeded($payload, 10000);
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals(10000, $i->valor_kz);
        $this->assertEquals(3, $i->mini_cursos_count);
    }

    public function test_docente_que_diz_ser_ispm_mas_sigam_nao_confirma(): void
    {
        $this->fakeSigam(isDocente: false);

        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'categoria'           => 'docente',
                'modalidade'          => 'mini_curso',
                'instituicao'         => 'ISPM',
                'email_institucional' => 'naoexiste@ispm.ao',
                'mini_cursos'         => ['dia1_11h_ia_inclusao'],
            ]),
            5000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertFalse($i->is_docente_ispm);
        $this->assertEquals(5000, $i->valor_kz);
    }

    public function test_docente_ispm_participacao_sem_mini_curso_falha(): void
    {
        $this->fakeSigam(isDocente: true);

        $payload = $this->basePayload([
            'categoria'           => 'docente',
            'modalidade'          => 'participacao',
            'instituicao'         => 'ISPM',
            'email_institucional' => 'docente@ispm.ao',
            // sem mini_cursos
        ]);
        $this->post(route('inscricao.store'), $payload)
            ->assertSessionHasErrors(['mini_cursos']);

        $this->assertNull(Inscricao::firstWhere('email', 'joao@example.com'));
    }

    // =========================================================
    // VALIDAÇÃO de pagamento (valor declarado divergente)
    // =========================================================

    public function test_docente_ispm_segunda_vez_perde_beneficio(): void
    {
        $this->fakeSigam(isDocente: true);

        // 1.ª inscrição — usa o benefício (gratuita)
        $payload1 = $this->basePayload([
            'email'               => 'primeiro@example.com',
            'categoria'           => 'docente',
            'modalidade'          => 'participacao',
            'instituicao'         => 'ISPM',
            'email_institucional' => 'docente@ispm.ao',
            'mini_cursos'         => ['dia1_14h_ia_generativa'],
        ]);
        $this->post(route('inscricao.store'), $payload1)->assertRedirect();

        $i1 = Inscricao::where('email', 'primeiro@example.com')->first();
        $this->assertTrue($i1->is_docente_ispm);
        $this->assertEquals(0, $i1->valor_kz);

        // 2.ª inscrição com o MESMO email institucional — perde o benefício
        $payload2 = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'email'               => 'segundo@example.com',
                'categoria'           => 'docente',
                'modalidade'          => 'mini_curso',
                'instituicao'         => 'ISPM',
                'email_institucional' => 'docente@ispm.ao', // mesmo
                'mini_cursos'         => ['dia1_11h_ia_inclusao'],
            ]),
            5000 // sem benefício, paga 5.000 Kz
        );
        $this->post(route('inscricao.store'), $payload2)->assertRedirect();

        $i2 = Inscricao::where('email', 'segundo@example.com')->first();
        $this->assertFalse($i2->is_docente_ispm); // benefício removido
        $this->assertEquals(5000, $i2->valor_kz);
    }

    public function test_endpoint_verificar_docente_devolve_beneficio_usado(): void
    {
        $this->fakeSigam(isDocente: true);

        // Cria inscrição prévia que já consumiu o benefício
        Inscricao::create([
            'nome' => 'Anterior', 'email' => 'a@x.com', 'telefone' => '900',
            'email_institucional' => 'doc@ispm.ao', 'is_docente_ispm' => true,
            'categoria' => 'docente', 'modalidade' => 'participacao',
            'mini_cursos' => ['dia1_14h_ia_generativa'],
            'valor_kz' => 0, 'estado' => 'confirmada',
        ]);

        $response = $this->post(route('inscricao.verificar_docente'), [
            'email' => 'doc@ispm.ao',
        ]);

        $response->assertOk();
        $json = $response->json();
        $this->assertTrue($json['ok']);
        $this->assertTrue($json['beneficio_usado']);
        $this->assertFalse($json['is_docente']);
    }

    public function test_inscricao_rejeitada_nao_consome_beneficio(): void
    {
        $this->fakeSigam(isDocente: true);

        // Inscrição prévia REJEITADA — não deve contar
        Inscricao::create([
            'nome' => 'Rej', 'email' => 'r@x.com', 'telefone' => '900',
            'email_institucional' => 'doc@ispm.ao', 'is_docente_ispm' => true,
            'categoria' => 'docente', 'modalidade' => 'participacao',
            'mini_cursos' => ['dia1_14h_ia_generativa'],
            'valor_kz' => 0, 'estado' => 'rejeitada',
        ]);

        $payload = $this->basePayload([
            'categoria'           => 'docente',
            'modalidade'          => 'participacao',
            'instituicao'         => 'ISPM',
            'email_institucional' => 'doc@ispm.ao',
            'mini_cursos'         => ['dia1_11h_ia_inclusao'],
        ]);
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::where('email', 'joao@example.com')->first();
        $this->assertTrue($i->is_docente_ispm);
        $this->assertEquals(0, $i->valor_kz);
    }

    public function test_pagamento_divergente_marca_validacao_divergente(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload([
                'categoria'   => 'estudante',
                'modalidade'  => 'mini_curso',
                'mini_cursos' => ['dia1_14h_ia_generativa'],
            ]),
            3000,
            valorPago: 2500 // diverge
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        $i = Inscricao::firstWhere('email', 'joao@example.com');
        $this->assertEquals('divergente', $i->validacao_pagamento);
    }

    public function test_pagina_inscricao_renderiza_checkboxes_com_chaves_certas(): void
    {
        $response = $this->get(route('inscricao.create'));
        $response->assertOk();
        // Garante que os checkboxes têm os keys correctos do MINI_CURSOS, não índices numéricos
        foreach (array_keys(\App\Models\Inscricao::MINI_CURSOS) as $key) {
            $response->assertSee('value="' . $key . '"', false);
        }
        $response->assertDontSee('name="mini_cursos[]" value="0"', false);
    }

    public function test_email_enviado_em_todas_inscricoes(): void
    {
        $payload = $this->withFakeComprovativoIfNeeded(
            $this->basePayload(['categoria' => 'publico', 'modalidade' => 'participacao']),
            10000
        );
        $this->post(route('inscricao.store'), $payload)->assertRedirect();

        Mail::assertSent(InscricaoConfirmada::class, function ($mail) {
            return $mail->hasTo('joao@example.com');
        });
    }
}