<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricoes';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'instituicao',
        'categoria',
        'modalidade',
        'mini_cursos',
        'valor_kz',
        'comprovativo_path',
        'estado',
        'observacoes',
    ];

    protected $casts = [
        'mini_cursos' => 'array',
    ];

    public const CATEGORIAS = [
        'docente'   => 'Docente',
        'estudante' => 'Estudante',
        'publico'   => 'Público em Geral',
    ];

    public const MODALIDADES = [
        'participacao' => 'Participação',
        'mini_curso'   => 'Mini-Curso',
    ];

    public const TABELA_PRECOS = [
        'docente'   => ['participacao' => 10000, 'mini_curso' => 5000],
        'estudante' => ['participacao' => 2000,  'mini_curso' => 3000],
        'publico'   => ['participacao' => 10000, 'mini_curso' => 5000],
    ];

    public const MINI_CURSOS = [
        'dia1_11h_ia_inclusao' => [
            'dia'        => '1.º Dia · 11 Jun',
            'hora'       => '11h00',
            'local'      => 'Sala de Conferência',
            'tema'       => 'IA · Educação',
            'titulo'     => 'Inteligência artificial como ferramenta de inclusão educativa: potencialidade e limites',
            'prelector'  => 'Sérgio Cespo Coelho da Silva Pinto · Prof. associado, Univ. Fluminense (Brasil)',
            'moderador'  => 'José Filipe, MSc.',
        ],
        'dia1_11h_seguranca' => [
            'dia'        => '1.º Dia · 11 Jun',
            'hora'       => '11h00',
            'local'      => 'Sala 33',
            'tema'       => 'Saúde & Higiene',
            'titulo'     => 'Segurança, Saúde e Higiene no Trabalho — Nível 1',
            'prelector'  => 'José Mulangue, PhD',
            'moderador'  => 'Luis Gomes, Lic.',
        ],
        'dia1_14h_ia_generativa' => [
            'dia'        => '1.º Dia · 11 Jun',
            'hora'       => '14h00',
            'local'      => 'Sala de Conferência',
            'tema'       => 'IA Generativa',
            'titulo'     => 'Inteligência artificial generativa: ferramenta para a pesquisa científica',
            'prelector'  => 'Ilma Rodrigues de Souza Fausto · Prof. associada, Univ. Fluminense (Brasil)',
            'moderador'  => 'José Filipe, MSc.',
        ],
        'dia1_14h_capital_humano' => [
            'dia'        => '1.º Dia · 11 Jun',
            'hora'       => '14h00',
            'local'      => 'Sala 32',
            'tema'       => 'Capital Humano',
            'titulo'     => 'Importância do Qualificador Ocupacional na Gestão do Capital Humano à luz do Decreto Presidencial 96/22',
            'prelector'  => 'Gaudêncio Félix, MSc.',
            'moderador'  => 'Eunice Pedro, Lic.',
        ],
        'dia1_15h_ia_negocios' => [
            'dia'        => '1.º Dia · 11 Jun',
            'hora'       => '15h30',
            'local'      => 'Sala 32',
            'tema'       => 'IA · Negócios',
            'titulo'     => 'Inteligência artificial degenerativa: transformando o trabalho, a educação e os negócios',
            'prelector'  => 'Yuniel Pena Gonzalez, MSc.',
            'moderador'  => 'José Filipe, MSc.',
        ],
        'dia1_15h_projectos' => [
            'dia'        => '1.º Dia · 11 Jun',
            'hora'       => '15h30',
            'local'      => 'Sala de Conferência',
            'tema'       => 'Investigação',
            'titulo'     => 'Elaboração de projectos de investigação para captação de fontes de financiamento',
            'prelector'  => 'Arnaldo Faustino, PhD',
            'moderador'  => 'José Filipe, MSc.',
        ],
        'dia2_09h_educacao_inclusiva' => [
            'dia'        => '2.º Dia · 12 Jun',
            'hora'       => '09h00',
            'local'      => 'Sala de Conferência',
            'tema'       => 'Educação Inclusiva',
            'titulo'     => 'Educação inclusiva no século XXI: estratégias para reduzir desigualdades educacionais em Angola',
            'prelector'  => 'Ruth Mariani Braz · Prof. associada, Univ. Fluminense (Brasil)',
            'moderador'  => 'José Filipe, MSc.',
        ],
    ];

    public static function calcularValor(string $categoria, string $modalidade, int $quantidade = 1): int
    {
        $base = self::TABELA_PRECOS[$categoria][$modalidade] ?? 0;
        if ($modalidade === 'mini_curso') {
            return $base * max(1, $quantidade);
        }
        return $base;
    }

    public function getCategoriaLabelAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? $this->categoria;
    }

    public function getModalidadeLabelAttribute(): string
    {
        return self::MODALIDADES[$this->modalidade] ?? $this->modalidade;
    }

    public function getMiniCursosListAttribute(): array
    {
        $chaves = is_array($this->mini_cursos) ? $this->mini_cursos : [];
        $out = [];
        foreach ($chaves as $k) {
            if (isset(self::MINI_CURSOS[$k])) {
                $mc = self::MINI_CURSOS[$k];
                $out[] = $mc['hora'] . ' · ' . $mc['local'] . ' — ' . $mc['titulo'];
            }
        }
        return $out;
    }

    public function getMiniCursosCountAttribute(): int
    {
        return is_array($this->mini_cursos) ? count($this->mini_cursos) : 0;
    }

    public function getValorFormatadoAttribute(): string
    {
        return number_format($this->valor_kz, 2, ',', '.') . ' Kz';
    }
}
