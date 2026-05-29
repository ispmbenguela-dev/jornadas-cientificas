<?php

namespace Tests\Feature;

use App\Models\Edicao;
use App\Models\Inscricao;
use App\Models\MiniCurso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MultiEdicaoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    public function test_criar_edicao_actual_despromove_anterior(): void
    {
        $antiga = Edicao::create([
            'numero_romano' => 'X', 'numero_inteiro' => 10, 'slug' => 'x-2024',
            'nome' => 'X Jornada', 'ano' => 2024,
            'data_inicio' => '2024-03-21', 'data_fim' => '2024-03-22',
            'status' => 'actual',
        ]);

        $nova = Edicao::create([
            'numero_romano' => 'XI', 'numero_inteiro' => 11, 'slug' => 'xi-2026',
            'nome' => 'XI Jornada', 'ano' => 2026,
            'data_inicio' => '2026-06-11', 'data_fim' => '2026-06-12',
            'status' => 'actual',
        ]);

        $this->assertEquals('passada', $antiga->fresh()->status);
        $this->assertEquals('actual', $nova->fresh()->status);
    }

    public function test_taxas_por_edicao_sao_aplicadas(): void
    {
        $edicao = Edicao::create([
            'numero_romano' => 'XII', 'numero_inteiro' => 12, 'slug' => 'xii-2027',
            'nome' => 'XII Jornada', 'ano' => 2027,
            'data_inicio' => '2027-06-01', 'data_fim' => '2027-06-02',
            'status' => 'actual',
            'taxas' => [
                'docente'   => ['participacao' => 25000, 'mini_curso' => 12000],
                'estudante' => ['participacao' => 5000,  'mini_curso' => 7000],
                'publico'   => ['participacao' => 25000, 'mini_curso' => 12000],
            ],
        ]);

        $this->assertEquals(5000, $edicao->calcularValor('estudante', 'participacao'));
        $this->assertEquals(14000, $edicao->calcularValor('estudante', 'mini_curso', 2));
        $this->assertEquals(0, $edicao->calcularValor('docente', 'mini_curso', 1, true));
        $this->assertEquals(12000, $edicao->calcularValor('docente', 'mini_curso', 2, true));
    }

    public function test_inscricao_grava_edicao_id(): void
    {
        $edicao = Edicao::create([
            'numero_romano' => 'XII', 'numero_inteiro' => 12, 'slug' => 'xii-2027',
            'nome' => 'XII Jornada', 'ano' => 2027,
            'data_inicio' => '2027-06-01', 'data_fim' => '2027-06-02',
            'status' => 'actual',
            'taxas' => Edicao::TAXAS_DEFAULT,
        ]);

        MiniCurso::create([
            'edicao_id' => $edicao->id, 'chave' => 'demo',
            'dia_label' => 'Dia 1', 'hora' => '10h', 'local' => 'Sala A',
            'tema' => 'Demo', 'titulo' => 'Mini demo',
        ]);

        $this->post(route('inscricao.store'), [
            'nome' => 'X', 'email' => 'x@y.com', 'telefone' => '900',
            'instituicao' => 'UAN',
            'categoria' => 'estudante', 'modalidade' => 'mini_curso',
            'mini_cursos' => ['demo'],
            'valor_pago_informado' => 3000,
            'referencia_pagamento' => 'REF-XYZ',
            'comprovativo' => UploadedFile::fake()->create('c.pdf', 50, 'application/pdf'),
        ])->assertRedirect();

        $i = Inscricao::firstWhere('email', 'x@y.com');
        $this->assertNotNull($i);
        $this->assertEquals($edicao->id, $i->edicao_id);
        $this->assertEquals(3000, $i->valor_kz);
    }

    public function test_pagina_arquivo_lista_edicoes_passadas(): void
    {
        Edicao::create([
            'numero_romano' => 'I', 'numero_inteiro' => 1, 'slug' => 'i-2020',
            'nome' => 'I Jornada', 'ano' => 2020,
            'data_inicio' => '2020-05-01', 'data_fim' => '2020-05-02',
            'status' => 'passada', 'mostrar_no_arquivo' => true,
        ]);

        $this->get(route('edicoes.index'))
            ->assertOk()
            ->assertSee('I Jornada');
    }

    public function test_pagina_edicao_passada_acessivel_por_slug(): void
    {
        Edicao::create([
            'numero_romano' => 'III', 'numero_inteiro' => 3, 'slug' => 'iii-2026',
            'nome' => 'III Jornadas Científicas', 'ano' => 2026,
            'data_inicio' => '2026-05-08', 'data_fim' => '2026-05-08',
            'status' => 'passada', 'mostrar_no_arquivo' => true,
        ]);

        $this->get(route('edicoes.show', 'iii-2026'))
            ->assertOk()
            ->assertSee('III Jornadas Científicas');
    }

    public function test_admin_cria_edicao_via_formulario(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.edicoes.store'), [
            'numero_romano' => 'XII', 'numero_inteiro' => 12,
            'slug' => 'xii-2027', 'nome' => 'XII Jornada',
            'tipo' => 'geral', 'ano' => 2027,
            'data_inicio' => '2027-06-10', 'data_fim' => '2027-06-11',
            'status' => 'futura',
            'taxa_docente_part' => 12000, 'taxa_docente_mini' => 6000,
            'taxa_estudante_part' => 3000, 'taxa_estudante_mini' => 4000,
            'taxa_publico_part' => 12000, 'taxa_publico_mini' => 6000,
        ])->assertRedirect();

        $e = Edicao::where('slug', 'xii-2027')->first();
        $this->assertNotNull($e);
        $this->assertEquals(12000, $e->getTaxa('docente', 'participacao'));
    }

    public function test_seeder_cria_xi_e_iii(): void
    {
        $this->artisan('db:seed', ['--class' => 'Database\Seeders\EdicaoSeeder', '--no-interaction' => true])
            ->assertExitCode(0);

        $this->assertNotNull(Edicao::where('slug', 'xi-2026')->first());
        $this->assertNotNull(Edicao::where('slug', 'iii-2026')->first());
        $this->assertEquals(7, Edicao::where('slug', 'xi-2026')->first()->miniCursos()->count());
    }

    public function test_inscricao_usa_taxas_da_edicao_actual(): void
    {
        Edicao::create([
            'numero_romano' => 'XII', 'numero_inteiro' => 12, 'slug' => 'xii-2027',
            'nome' => 'XII', 'ano' => 2027,
            'data_inicio' => '2027-06-01', 'data_fim' => '2027-06-02',
            'status' => 'actual',
            'taxas' => [
                'docente'   => ['participacao' => 99000, 'mini_curso' => 50000],
                'estudante' => ['participacao' => 1000,  'mini_curso' => 2000],
                'publico'   => ['participacao' => 99000, 'mini_curso' => 50000],
            ],
        ]);

        $this->post(route('inscricao.store'), [
            'nome' => 'Z', 'email' => 'z@y.com', 'telefone' => '900',
            'instituicao' => 'UAN',
            'categoria' => 'estudante', 'modalidade' => 'participacao',
            'valor_pago_informado' => 1000,
            'referencia_pagamento' => 'REF',
            'comprovativo' => UploadedFile::fake()->create('c.pdf', 50, 'application/pdf'),
        ])->assertRedirect();

        $this->assertEquals(1000, Inscricao::firstWhere('email', 'z@y.com')->valor_kz);
    }
}
