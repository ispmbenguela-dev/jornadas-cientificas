<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Edicao extends Model
{
    protected $table = 'edicoes';

    protected $fillable = [
        'numero_romano',
        'numero_inteiro',
        'slug',
        'nome',
        'nome_curto',
        'tipo',
        'ano',
        'lema',
        'descricao',
        'data_inicio',
        'data_fim',
        'local',
        'inscricao_inicio',
        'inscricao_fim',
        'submissao_inicio',
        'submissao_fim',
        'trabalhos_admitidos_inicio',
        'trabalhos_admitidos_fim',
        'presidente_nome',
        'presidente_titulo',
        'status',
        'taxas',
        'banner_path',
        'cor_primaria',
        'cor_secundaria',
        'mostrar_no_arquivo',
    ];

    protected $casts = [
        'data_inicio'                => 'date',
        'data_fim'                   => 'date',
        'inscricao_inicio'           => 'date',
        'inscricao_fim'              => 'date',
        'submissao_inicio'           => 'date',
        'submissao_fim'              => 'date',
        'trabalhos_admitidos_inicio' => 'date',
        'trabalhos_admitidos_fim'    => 'date',
        'taxas'                      => 'array',
        'mostrar_no_arquivo'         => 'boolean',
    ];

    public const STATUS = [
        'futura'  => 'Futura',
        'actual'  => 'Actual',
        'passada' => 'Passada',
    ];

    public const TIPOS = [
        'geral'          => 'Jornada Geral',
        'departamental'  => 'Jornada Departamental',
        'especial'       => 'Jornada Especial',
    ];

    public const TAXAS_DEFAULT = [
        'docente'   => ['participacao' => 10000, 'mini_curso' => 5000],
        'estudante' => ['participacao' => 2000,  'mini_curso' => 3000],
        'publico'   => ['participacao' => 10000, 'mini_curso' => 5000],
        'pta'       => ['participacao' => 2000, 'mini_curso' => 3000],
        'mco'       => ['participacao' => 0,     'mini_curso' => 0],
    ];

    protected static function booted(): void
    {
        static::saved(function (Edicao $edicao) {
            // Apenas uma edição pode ser 'actual' simultaneamente.
            if ($edicao->status === 'actual') {
                static::query()
                    ->where('id', '!=', $edicao->id)
                    ->where('status', 'actual')
                    ->update(['status' => 'passada']);
            }
        });
    }

    /**
     * Retorna a edição actual (cria default XI 2026 se nenhuma existir).
     */
    public static function atual(): self
    {
        $atual = static::where('status', 'actual')->first();
        if ($atual) {
            return $atual;
        }
        return static::criarPadrao();
    }

    public static function criarPadrao(): self
    {
        return static::firstOrCreate(
            ['slug' => 'xi-2026'],
            [
                'numero_romano'              => 'XI',
                'numero_inteiro'             => 11,
                'nome'                       => 'XI Jornada Científico-Metodológica Geral',
                'nome_curto'                 => 'XI Jornada',
                'tipo'                       => 'geral',
                'ano'                        => 2026,
                'lema'                       => 'O Ensino Superior e os desafios do Desenvolvimento Económico e Social de Angola',
                'data_inicio'                => '2026-06-11',
                'data_fim'                   => '2026-06-12',
                'local'                      => 'ISPM · Av. Aires de Almeida Santos · Benguela',
                'inscricao_inicio'           => '2026-05-18',
                'inscricao_fim'              => '2026-06-05',
                'submissao_inicio'           => '2026-05-04',
                'submissao_fim'              => '2026-05-28',
                'trabalhos_admitidos_inicio' => '2026-05-18',
                'trabalhos_admitidos_fim'    => '2026-05-24',
                'status'                     => 'actual',
                'taxas'                      => self::TAXAS_DEFAULT,
            ]
        );
    }

    public function miniCursos(): HasMany
    {
        return $this->hasMany(MiniCurso::class)->orderBy('ordem');
    }

    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class);
    }

    public function submissoes(): HasMany
    {
        return $this->hasMany(Submissao::class);
    }

    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class);
    }

    // ----- Helpers de domínio -----

    public function getTaxa(string $categoria, string $modalidade): int
    {
        $taxas = $this->taxas ?? self::TAXAS_DEFAULT;
        return (int) ($taxas[$categoria][$modalidade] ?? 0);
    }

    public function calcularValor(string $categoria, string $modalidade, int $quantidade = 1, bool $isDocenteIspm = false): int
    {
        $base = $this->getTaxa($categoria, $modalidade);
        if ($modalidade === 'participacao') {
            return $isDocenteIspm ? 0 : $base;
        }
        $qtd = max(1, $quantidade);
        if ($isDocenteIspm) {
            return $base * max(0, $qtd - 1);
        }
        return $base * $qtd;
    }

    public function inscricaoAberta(): bool
    {
        if (!$this->inscricao_fim) {
            return $this->status === 'actual';
        }
        return now()->lte($this->inscricao_fim->endOfDay())
            && now()->gte(($this->inscricao_inicio ?? $this->data_inicio)->startOfDay());
    }

    public function submissaoAberta(): bool
    {
        if (!$this->submissao_fim) {
            return false;
        }
        return now()->lte($this->submissao_fim->endOfDay())
            && (!$this->submissao_inicio || now()->gte($this->submissao_inicio->startOfDay()));
    }

    public function getDataExtensoAttribute(): string
    {
        if (!$this->data_inicio || !$this->data_fim) {
            return '';
        }
        if ($this->data_inicio->isSameDay($this->data_fim)) {
            return $this->data_inicio->translatedFormat('d \d\e F \d\e Y');
        }
        if ($this->data_inicio->month === $this->data_fim->month) {
            return $this->data_inicio->day . ' e ' . $this->data_fim->translatedFormat('d \d\e F \d\e Y');
        }
        return $this->data_inicio->translatedFormat('d \d\e F')
            . ' a ' . $this->data_fim->translatedFormat('d \d\e F \d\e Y');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_path ? asset('storage/' . ltrim($this->banner_path, '/')) : null;
    }
}
