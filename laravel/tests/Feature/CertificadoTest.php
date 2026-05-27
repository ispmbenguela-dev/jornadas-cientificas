<?php

namespace Tests\Feature;

use App\Models\Certificado;
use App\Models\Inscricao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_codigo_unico_e_emitido_em_atribuidos_automaticamente(): void
    {
        $c = Certificado::create([
            'tipo'        => 'participante',
            'nome'        => 'Maria Teste',
            'data_evento' => '2026-06-12',
        ]);

        $this->assertNotEmpty($c->codigo);
        $this->assertStringStartsWith('XI-26-', $c->codigo);
        $this->assertNotNull($c->emitido_em);
    }

    public function test_corpo_texto_diferente_por_tipo(): void
    {
        $participante = Certificado::create([
            'tipo' => 'participante', 'nome' => 'A', 'data_evento' => '2026-06-12',
        ]);
        $prelectorMini = Certificado::create([
            'tipo' => 'prelector_mini_curso', 'nome' => 'B', 'tema' => 'IA na Educação', 'data_evento' => '2026-06-12',
        ]);
        $prelectorCom = Certificado::create([
            'tipo' => 'prelector_comunicacao', 'nome' => 'C', 'tema' => 'Segurança', 'data_evento' => '2026-06-12',
        ]);

        $this->assertStringContainsString('participou na XI Jornada', $participante->corpo_texto);
        $this->assertStringContainsString('participou no mini-curso como prelector', $prelectorMini->corpo_texto);
        $this->assertStringContainsString('IA na Educação', $prelectorMini->corpo_texto);
        $this->assertStringContainsString('ministrou com êxito, a comunicação livre', $prelectorCom->corpo_texto);
    }

    public function test_relacao_inscricao_funciona(): void
    {
        $i = Inscricao::create([
            'nome' => 'João', 'email' => 'j@x.com', 'telefone' => '900',
            'categoria' => 'estudante', 'modalidade' => 'participacao',
            'valor_kz' => 2000, 'estado' => 'confirmada',
        ]);

        $c = Certificado::create([
            'tipo' => 'participante',
            'inscricao_id' => $i->id,
            'nome' => 'João',
            'data_evento' => '2026-06-12',
        ]);

        $this->assertEquals($i->id, $c->inscricao->id);
        $this->assertEquals('j@x.com', $c->email_destino);
        $this->assertEquals($c->id, $i->fresh()->certificadoParticipante->id);
    }

    public function test_qr_code_data_uri_gerado(): void
    {
        $c = Certificado::create([
            'tipo' => 'participante', 'nome' => 'X', 'data_evento' => '2026-06-12',
        ]);

        $uri = $c->qr_data_uri;
        $this->assertNotNull($uri);
        $this->assertStringStartsWith('data:image/svg+xml;base64,', $uri);
    }

    public function test_verify_url_inclui_codigo(): void
    {
        $c = Certificado::create([
            'tipo' => 'participante', 'nome' => 'Y', 'data_evento' => '2026-06-12',
        ]);

        $this->assertStringContainsString('/certificado/' . $c->codigo, $c->verify_url);
    }

    public function test_relacao_submissao_certificado_prelector(): void
    {
        $s = \App\Models\Submissao::create([
            'titulo' => 'Tema X',
            'autor_principal' => 'Autor Y',
            'email' => 'autor@x.com',
            'telefone' => '900',
            'instituicao' => 'ISPM',
            'area_tematica' => 'Outras',
            'resumo' => 'lorem',
            'ficheiro_path' => 'x.pdf',
            'ficheiro_original' => 'x.pdf',
            'estado' => 'admitida',
        ]);

        $c = Certificado::create([
            'tipo' => 'prelector_comunicacao',
            'submissao_id' => $s->id,
            'nome' => 'Autor Y',
            'tema' => 'Tema X',
            'data_evento' => '2026-06-12',
        ]);

        $this->assertEquals('autor@x.com', $c->email_destino);
        $this->assertEquals($c->id, $s->fresh()->certificadoPrelector->id);
    }
}