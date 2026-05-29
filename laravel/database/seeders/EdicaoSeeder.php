<?php

namespace Database\Seeders;

use App\Models\Edicao;
use App\Models\MiniCurso;
use Illuminate\Database\Seeder;

class EdicaoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedXI2026();
        $this->seedIII2026();
    }

    private function seedXI2026(): void
    {
        $edicao = Edicao::updateOrCreate(
            ['slug' => 'xi-2026'],
            [
                'numero_romano'              => 'XI',
                'numero_inteiro'             => 11,
                'nome'                       => 'XI Jornada Científico-Metodológica Geral',
                'nome_curto'                 => 'XI Jornada',
                'tipo'                       => 'geral',
                'ano'                        => 2026,
                'lema'                       => 'O Ensino Superior e os desafios do Desenvolvimento Económico e Social de Angola',
                'descricao'                  => 'Dois dias dedicados à investigação, ao ensino superior e ao papel da ciência no progresso de Angola.',
                'data_inicio'                => '2026-06-11',
                'data_fim'                   => '2026-06-12',
                'local'                      => 'ISPM · Av. Aires de Almeida Santos · Benguela',
                'inscricao_inicio'           => '2026-05-18',
                'inscricao_fim'              => '2026-06-05',
                'submissao_inicio'           => '2026-05-04',
                'submissao_fim'              => '2026-05-28',
                'trabalhos_admitidos_inicio' => '2026-05-18',
                'trabalhos_admitidos_fim'    => '2026-05-24',
                'presidente_nome'            => 'JOSÉ JANUÁRIO, PhD.',
                'status'                     => 'actual',
                'taxas'                      => Edicao::TAXAS_DEFAULT,
                'cor_primaria'               => '#0f4c81',
                'cor_secundaria'             => '#f37021',
                'mostrar_no_arquivo'         => true,
            ]
        );

        $miniCursos = [
            [
                'chave' => 'dia1_11h_ia_inclusao',
                'dia_label' => '1.º Dia · 11 Jun', 'hora' => '11h00',
                'local' => 'Sala de Conferência', 'tema' => 'IA · Educação',
                'titulo' => 'Inteligência artificial como ferramenta de inclusão educativa: potencialidade e limites',
                'prelector' => 'Sérgio Cespo Coelho da Silva Pinto · Prof. associado, Univ. Fluminense (Brasil)',
                'moderador' => 'José Filipe, MSc.',
            ],
            [
                'chave' => 'dia1_11h_seguranca',
                'dia_label' => '1.º Dia · 11 Jun', 'hora' => '11h00',
                'local' => 'Sala 33', 'tema' => 'Saúde & Higiene',
                'titulo' => 'Segurança, Saúde e Higiene no Trabalho — Nível 1',
                'prelector' => 'José Mulangue, PhD',
                'moderador' => 'Luis Gomes, Lic.',
            ],
            [
                'chave' => 'dia1_14h_ia_generativa',
                'dia_label' => '1.º Dia · 11 Jun', 'hora' => '14h00',
                'local' => 'Sala de Conferência', 'tema' => 'IA Generativa',
                'titulo' => 'Inteligência artificial generativa: ferramenta para a pesquisa científica',
                'prelector' => 'Ilma Rodrigues de Souza Fausto · Prof. associada, Univ. Fluminense (Brasil)',
                'moderador' => 'José Filipe, MSc.',
            ],
            [
                'chave' => 'dia1_14h_capital_humano',
                'dia_label' => '1.º Dia · 11 Jun', 'hora' => '14h00',
                'local' => 'Sala 32', 'tema' => 'Capital Humano',
                'titulo' => 'Importância do Qualificador Ocupacional na Gestão do Capital Humano à luz do Decreto Presidencial 96/22',
                'prelector' => 'Gaudêncio Félix, MSc.',
                'moderador' => 'Eunice Pedro, Lic.',
            ],
            [
                'chave' => 'dia1_15h_ia_negocios',
                'dia_label' => '1.º Dia · 11 Jun', 'hora' => '15h30',
                'local' => 'Sala 32', 'tema' => 'IA · Negócios',
                'titulo' => 'Inteligência artificial degenerativa: transformando o trabalho, a educação e os negócios',
                'prelector' => 'Yuniel Pena Gonzalez, MSc.',
                'moderador' => 'José Filipe, MSc.',
            ],
            [
                'chave' => 'dia1_15h_projectos',
                'dia_label' => '1.º Dia · 11 Jun', 'hora' => '15h30',
                'local' => 'Sala de Conferência', 'tema' => 'Investigação',
                'titulo' => 'Elaboração de projectos de investigação para captação de fontes de financiamento',
                'prelector' => 'Arnaldo Faustino, PhD',
                'moderador' => 'José Filipe, MSc.',
            ],
            [
                'chave' => 'dia2_09h_educacao_inclusiva',
                'dia_label' => '2.º Dia · 12 Jun', 'hora' => '09h00',
                'local' => 'Sala de Conferência', 'tema' => 'Educação Inclusiva',
                'titulo' => 'Educação inclusiva no século XXI: estratégias para reduzir desigualdades educacionais em Angola',
                'prelector' => 'Ruth Mariani Braz · Prof. associada, Univ. Fluminense (Brasil)',
                'moderador' => 'José Filipe, MSc.',
            ],
        ];

        foreach ($miniCursos as $i => $mc) {
            MiniCurso::updateOrCreate(
                ['edicao_id' => $edicao->id, 'chave' => $mc['chave']],
                array_merge($mc, ['ordem' => $i + 1, 'activo' => true])
            );
        }
    }

    private function seedIII2026(): void
    {
        Edicao::updateOrCreate(
            ['slug' => 'iii-2026'],
            [
                'numero_romano'      => 'III',
                'numero_inteiro'     => 3,
                'nome'               => 'III Jornadas Científicas dos Departamentos CEJ e CT',
                'nome_curto'         => 'III Jornadas',
                'tipo'               => 'departamental',
                'ano'                => 2026,
                'lema'               => 'A Inteligência Artificial e os Desafios do Desenvolvimento Económico',
                'descricao'          => 'Edição departamental de Ciências Económicas Jurídicas e Ciência e Tecnologia.',
                'data_inicio'        => '2026-05-08',
                'data_fim'           => '2026-05-08',
                'local'              => 'Anfiteatro do ISPM · Benguela',
                'presidente_nome'    => 'JOSÉ JANUÁRIO, PhD.',
                'status'             => 'passada',
                'taxas'              => Edicao::TAXAS_DEFAULT,
                'mostrar_no_arquivo' => true,
            ]
        );
    }
}
