<?php

namespace Tests\Feature;

use App\Models\Configuracao;
use App\Models\Submissao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubmissaoPrazoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function payloadValido(): array
    {
        return [
            'titulo'          => 'Estudo X',
            'autor_principal' => 'Autor Y',
            'email'           => 'autor@x.com',
            'telefone'        => '900',
            'instituicao'     => 'ISPM',
            'area_tematica'   => 'Outras',
            'resumo'          => 'Resumo de teste com mais de cinquenta caracteres para passar.',
            'ficheiro'        => UploadedFile::fake()->create('artigo.pdf', 100, 'application/pdf'),
        ];
    }

    public function test_default_prazo_quando_nada_configurado(): void
    {
        $prazo = Submissao::prazoFinal();
        $this->assertEquals('2026-05-28', $prazo->format('Y-m-d'));
        $this->assertEquals(23, $prazo->hour);
        $this->assertEquals(59, $prazo->minute);
    }

    public function test_configuracao_da_admin_altera_o_prazo(): void
    {
        Configuracao::set('submissao_prazo', '2026-06-15', 'date');
        $this->assertEquals('2026-06-15', Submissao::prazoFinal()->format('Y-m-d'));
    }

    public function test_formulario_aberto_antes_do_prazo(): void
    {
        Configuracao::set('submissao_prazo', now()->addDays(3)->format('Y-m-d'), 'date');
        $this->get(route('submissao.create'))
            ->assertOk()
            ->assertSee('Submeter', false);
    }

    public function test_formulario_encerrado_apos_o_prazo(): void
    {
        Configuracao::set('submissao_prazo', now()->subDays(1)->format('Y-m-d'), 'date');
        $this->get(route('submissao.create'))
            ->assertOk()
            ->assertSee('encerrado', false)
            ->assertDontSee('name="titulo"', false);
    }

    public function test_post_apos_prazo_e_rejeitado(): void
    {
        Configuracao::set('submissao_prazo', now()->subDays(2)->format('Y-m-d'), 'date');

        $this->post(route('submissao.store'), $this->payloadValido())
            ->assertRedirect(route('submissao.create'));

        $this->assertEquals(0, Submissao::count());
    }

    public function test_dia_do_prazo_ainda_aceita(): void
    {
        Configuracao::set('submissao_prazo', now()->format('Y-m-d'), 'date');
        $this->assertTrue(Submissao::aberta());

        $this->post(route('submissao.store'), $this->payloadValido())
            ->assertRedirect();

        $this->assertEquals(1, Submissao::count());
    }
}