<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Inscricao extends Model
{
    protected $table = 'inscricoes';

    protected $fillable = [
        'edicao_id',
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

    public static function calcularValor(string $categoria, string $modalidade, int $quantidade = 1, bool $isDocenteIspm = false, ?Edicao $edicao = null): int
    {
        if ($edicao) {
            return $edicao->calcularValor($categoria, $modalidade, $quantidade, $isDocenteIspm);
        }

        $base = self::TABELA_PRECOS[$categoria][$modalidade] ?? 0;
        if ($modalidade === 'participacao') {
            return $isDocenteIspm ? 0 : $base;
        }
        $qtd = max(1, $quantidade);
        if ($isDocenteIspm) {
            return $base * max(0, $qtd - 1);
        }
        return $base * $qtd;
    }

    /**
     * Mini-cursos disponíveis na edição actual (DB) com fallback para a constante.
     */
    public static function miniCursosDisponiveis(?Edicao $edicao = null): array
    {
        $edicao ??= Edicao::query()->where('status', 'actual')->first();
        if (!$edicao) {
            return self::MINI_CURSOS;
        }
        $rows = $edicao->miniCursos()->where('activo', true)->get();
        if ($rows->isEmpty()) {
            return self::MINI_CURSOS;
        }
        $out = [];
        foreach ($rows as $r) {
            $out[$r->chave] = $r->toCardArray();
        }
        return $out;
    }

    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class);
    }

    public static function isInstituicaoIspm(?string $instituicao): bool
    {
        if (!$instituicao) {
            return false;
        }
        $normalizado = mb_strtolower(trim($instituicao));
        return in_array($normalizado, self::INSTITUICAO_ISPM_ALIASES, true);
    }

    /**
     * Verifica se um docente ISPM já usou o seu benefício gratuito
     * (inscrição anterior com `is_docente_ispm = true`, não rejeitada).
     */
    public static function jaUsouBeneficioIspm(string $emailInstitucional, ?int $excluirId = null): bool
    {
        $query = self::where('email_institucional', $emailInstitucional)
            ->where('is_docente_ispm', true)
            ->whereIn('estado', ['pendente', 'confirmada']);

        if ($excluirId !== null) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
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
        $catalogo = self::miniCursosDisponiveis($this->edicao);
        // Inclui também MINI_CURSOS para retro-compatibilidade
        $catalogo = array_replace(self::MINI_CURSOS, $catalogo);
        $out = [];
        foreach ($chaves as $k) {
            if (isset($catalogo[$k])) {
                $mc = $catalogo[$k];
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

    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class);
    }

    public function certificadoParticipante(): HasOne
    {
        return $this->hasOne(Certificado::class)->where('tipo', 'participante');
    }
}
