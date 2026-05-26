<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscricao extends Model
{
    protected $table = 'inscricoes';

    protected $fillable = [
        'nome',
        'email',
        'email_institucional',
        'is_docente_ispm',
        'verificacao_sigam',
        'telefone',
        'instituicao',
        'categoria',
        'modalidade',
        'mini_cursos',
        'valor_kz',
        'valor_pago_informado',
        'referencia_pagamento',
        'validacao_pagamento',
        'comprovativo_path',
        'estado',
        'observacoes',
    ];

    protected $casts = [
        'mini_cursos'       => 'array',
        'verificacao_sigam' => 'array',
        'is_docente_ispm'   => 'boolean',
    ];

    public const VALIDACAO_LABELS = [
        'nao_aplicavel' => 'N/A',
        'pendente'      => 'Pendente',
        'ok'            => 'Valor confere',
        'divergente'    => 'Divergente',
    ];

    public const INSTITUICAO_ISPM_ALIASES = [
        'ispm',
        'instituto superior politécnico maravilha',
        'instituto superior politecnico maravilha',
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

    public static function calcularValor(string $categoria, string $modalidade, int $quantidade = 1, bool $isDocenteIspm = false): int
    {
        $base = self::TABELA_PRECOS[$categoria][$modalidade] ?? 0;

        if ($modalidade === 'participacao') {
            // Docente ISPM verificado: participação totalmente gratuita (inclui 1 mini-curso bónus).
            return $isDocenteIspm ? 0 : $base;
        }

        // modalidade = mini_curso
        $qtd = max(1, $quantidade);
        if ($isDocenteIspm) {
            return $base * max(0, $qtd - 1); // 1.º grátis
        }
        return $base * $qtd;
    }

    public static function isInstituicaoIspm(?string $instituicao): bool
    {
        if (!$instituicao) {
            return false;
        }
        $normalizado = mb_strtolower(trim($instituicao));
        return in_array($normalizado, self::INSTITUICAO_ISPM_ALIASES, true);
    }

    public function getValidacaoPagamentoLabelAttribute(): string
    {
        return self::VALIDACAO_LABELS[$this->validacao_pagamento] ?? $this->validacao_pagamento;
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
