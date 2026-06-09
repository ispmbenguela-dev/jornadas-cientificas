<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    protected $table = 'avaliacoes';

    protected $fillable = [
        'edicao_id', 'sexo', 'idade', 'categoria',
        'q1',  'q2',  'q3',  'q4',
        'q5',  'q6',  'q7',  'q8',
        'q9',  'q10', 'q11',
        'q12', 'q13', 'q14',
        'q15', 'q16', 'q17', 'q18',
        'q19', 'q20',
        'aspetos_positivos', 'sugestoes',
    ];

    protected $casts = [
        'idade' => 'integer',
    ];

    public const CATEGORIAS = [
        'estudante'    => 'Estudante',
        'docente'      => 'Docente',
        'investigador' => 'Investigador',
        'convidado'    => 'Convidado',
    ];

    public const SEXOS = [
        'M' => 'Masculino',
        'F' => 'Feminino',
    ];

    public const LIKERT = [
        1 => 'Muito Insatisfeito',
        2 => 'Insatisfeito',
        3 => 'Nem Satisfeito nem Insatisfeito',
        4 => 'Satisfeito',
        5 => 'Muito Satisfeito',
    ];

    public const SECOES = [
        'organizacao' => [
            'titulo'   => 'Organização do Evento',
            'icone'    => 'bi-clipboard-check',
            'perguntas' => [
                'q1' => 'Estou satisfeito com a organização geral das jornadas científicas.',
                'q2' => 'As informações sobre o evento foram divulgadas de forma clara e atempada.',
                'q3' => 'O processo de inscrição foi simples e eficiente.',
                'q4' => 'O cumprimento dos horários foi satisfatório.',
            ],
        ],
        'conteudo' => [
            'titulo'   => 'Conteúdo Científico',
            'icone'    => 'bi-journal-richtext',
            'perguntas' => [
                'q5' => 'Os temas apresentados foram relevantes para a minha formação profissional.',
                'q6' => 'As palestras e comunicações científicas atenderam às minhas expectativas.',
                'q7' => 'Os conteúdos apresentados contribuíram para a ampliação dos meus conhecimentos.',
                'q8' => 'A qualidade científica dos trabalhos apresentados foi satisfatória.',
            ],
        ],
        'palestrantes' => [
            'titulo'   => 'Palestrantes e Moderadores',
            'icone'    => 'bi-person-video3',
            'perguntas' => [
                'q9'  => 'Os palestrantes demonstraram domínio dos temas abordados.',
                'q10' => 'Os moderadores conduziram adequadamente as sessões.',
                'q11' => 'Houve oportunidade suficiente para perguntas e debates.',
            ],
        ],
        'infraestrutura' => [
            'titulo'   => 'Infraestruturas e Recursos',
            'icone'    => 'bi-building',
            'perguntas' => [
                'q12' => 'As condições físicas do local foram adequadas.',
                'q13' => 'Os recursos audiovisuais utilizados funcionaram adequadamente.',
                'q14' => 'O ambiente favoreceu a aprendizagem e a interação científica.',
            ],
        ],
        'impacto' => [
            'titulo'   => 'Impacto do Evento',
            'icone'    => 'bi-graph-up-arrow',
            'perguntas' => [
                'q15' => 'As jornadas científicas estimularam o meu interesse pela investigação científica.',
                'q16' => 'O evento promoveu a troca de experiências entre os participantes.',
                'q17' => 'Pretendo participar nas próximas edições das jornadas científicas.',
                'q18' => 'Recomendo a participação neste evento a outras pessoas.',
            ],
        ],
        'global' => [
            'titulo'   => 'Avaliação Global',
            'icone'    => 'bi-star',
            'perguntas' => [
                'q19' => 'De modo geral, estou satisfeito com as Jornadas Científicas do ISPM.',
                'q20' => 'As Jornadas Científicas corresponderam às minhas expectativas.',
            ],
        ],
    ];

    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class);
    }

    public function mediaGlobal(): float
    {
        $qs = [];
        foreach (self::SECOES as $secao) {
            foreach (array_keys($secao['perguntas']) as $q) {
                $qs[] = $q;
            }
        }
        $vals = array_map(fn($q) => (int) $this->$q, $qs);
        return round(array_sum($vals) / count($vals), 2);
    }

    public function mediaSecao(string $secao): float
    {
        $perguntas = self::SECOES[$secao]['perguntas'] ?? [];
        if (!$perguntas) return 0;
        $vals = array_map(fn($q) => (int) $this->$q, array_keys($perguntas));
        return round(array_sum($vals) / count($vals), 2);
    }

    public function getCategoriaLabelAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? $this->categoria;
    }

    public function getSexoLabelAttribute(): string
    {
        return self::SEXOS[$this->sexo] ?? $this->sexo;
    }
}