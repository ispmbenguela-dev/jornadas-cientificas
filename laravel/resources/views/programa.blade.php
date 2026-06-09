@extends('layouts.app')

@section('title', 'Programa — XI Jornada Científica ISPM')
@section('description', 'Programa completo da XI Jornada Científico-Metodológica Geral do ISPM — 11 e 12 de Junho de 2026.')

@section('content')

{{-- Hero --}}
<section class="section section-alt" style="padding-bottom:2rem">
    <div class="container text-center">
        <span class="section-eyebrow">11 e 12 de Junho de 2026</span>
        <h1 class="section-title">Programa <span class="text-accent">Científico</span></h1>
        <p class="section-lead mx-auto" style="max-width:680px">
            XI Jornada Científico-Metodológica Geral · ISPM – Sala de Conferência · Benguela
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
            <a href="#dia1" class="btn btn-cta btn-sm"><i class="bi bi-1-circle"></i> 1.º Dia · 11 Jun</a>
            <a href="#dia2" class="btn btn-outline-secondary btn-sm"><i class="bi bi-2-circle"></i> 2.º Dia · 12 Jun</a>
        </div>
    </div>
</section>

{{-- ===== 1.º DIA ===== --}}
<section class="section" id="dia1">
    <div class="container">

        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="prog-day-badge">1</div>
            <div>
                <h2 class="mb-0 fw-bold" style="font-size:1.5rem">1.º Dia — Quinta-feira, 11 de Junho de 2026</h2>
                <small class="text-muted"><i class="bi bi-geo-alt"></i> ISPM – Sala de Conferência</small>
            </div>
        </div>

        {{-- Abertura --}}
        <div class="prog-block prog-block-cerimonia mb-3">
            <div class="prog-time">08h00 – 09h00</div>
            <div class="prog-content">
                <div class="prog-tag">Protocolo</div>
                <div class="prog-titulo">Chegada e confirmação dos convidados</div>
            </div>
        </div>

        <div class="prog-block prog-block-cerimonia mb-3">
            <div class="prog-time">09h00</div>
            <div class="prog-content">
                <div class="prog-tag">Abertura</div>
                <div class="prog-titulo">Abertura do Evento</div>
                <ul class="prog-list">
                    <li>Entoação do Hino Nacional seguido de um minuto de silêncio</li>
                    <li>Apresentação da composição da Mesa do Presidium</li>
                    <li>Apresentação de todas as entidades académicas e governamentais convidadas</li>
                </ul>
            </div>
        </div>

        <div class="prog-block prog-block-cultural mb-3">
            <div class="prog-time">09h25 – 09h45</div>
            <div class="prog-content">
                <div class="prog-tag">Cultural</div>
                <div class="prog-titulo">Momento Cultural</div>
            </div>
        </div>

        <div class="prog-block prog-block-cerimonia mb-3">
            <div class="prog-time">09h45 – 09h55</div>
            <div class="prog-content">
                <div class="prog-tag">Comissão Organizadora</div>
                <div class="prog-titulo">Intervenção da Comissão Organizadora</div>
                <div class="prog-speaker">Vice-presidente para a Área Científica</div>
            </div>
        </div>

        <div class="prog-block prog-block-cerimonia mb-3">
            <div class="prog-time">10h05 – 10h15</div>
            <div class="prog-content">
                <div class="prog-tag">Discurso</div>
                <div class="prog-titulo">Discurso de Abertura</div>
                <div class="prog-speaker">Presidente do ISPM – Benguela</div>
            </div>
        </div>

        {{-- Conferência Magistral --}}
        <div class="prog-block prog-block-magistral mb-3">
            <div class="prog-time">10h15 – 11h30</div>
            <div class="prog-content">
                <div class="prog-tag prog-tag-magistral">Conferência Magistral</div>
                <div class="prog-titulo">
                    "O Ensino Superior e o Desenvolvimento em Angola: Desafios e Oportunidades"
                </div>
                <div class="prog-speaker">
                    <i class="bi bi-person-fill"></i>
                    Maria da Conceição Barbosa Mendes — Professora Catedrática e Presidente do Instituto Superior de Ciências da Educação de Benguela
                </div>
                <div class="prog-moderador">
                    <i class="bi bi-mic"></i> Moderador: Luís Caala Filipe, MSc.
                </div>
            </div>
        </div>

        <div class="prog-block prog-block-pausa mb-4">
            <div class="prog-time">12h00</div>
            <div class="prog-content">
                <div class="prog-tag">Pausa</div>
                <div class="prog-titulo">Coffee Break</div>
            </div>
        </div>

        {{-- Mini-cursos Dia 1 --}}
        <h4 class="prog-secao-titulo mb-3"><i class="bi bi-journal-bookmark-fill text-accent"></i> Mini-Cursos · 1.º Dia</h4>

        @if($miniCursos->has('1.º Dia · 11 Jun'))
            @php $mcDia1 = $miniCursos['1.º Dia · 11 Jun']->groupBy('hora'); @endphp
            @foreach($mcDia1 as $hora => $cursos)
            <div class="prog-horario-label mb-2"><i class="bi bi-clock"></i> {{ $hora }}</div>
            <div class="row g-3 mb-3">
                @foreach($cursos as $mc)
                <div class="col-md-6">
                    <div class="prog-block prog-block-minicurso h-100">
                        <div class="prog-time">{{ $mc->hora }}</div>
                        <div class="prog-content">
                            <div class="prog-tag prog-tag-mini">Mini-Curso</div>
                            <div class="prog-local"><i class="bi bi-geo-alt"></i> {{ $mc->local }}</div>
                            <div class="prog-titulo">{{ $mc->titulo }}</div>
                            <div class="prog-speaker"><i class="bi bi-person-fill"></i> {{ $mc->prelector }}</div>
                            <div class="prog-moderador"><i class="bi bi-mic"></i> Moderador: {{ $mc->moderador }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        @endif

        <div class="prog-block prog-block-cerimonia mt-3">
            <div class="prog-time">18h00</div>
            <div class="prog-content">
                <div class="prog-tag">Encerramento</div>
                <div class="prog-titulo">Encerramento do 1.º Dia</div>
            </div>
        </div>

    </div>
</section>

{{-- ===== 2.º DIA ===== --}}
<section class="section section-alt" id="dia2">
    <div class="container">

        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="prog-day-badge" style="background:var(--accent,#f37021)">2</div>
            <div>
                <h2 class="mb-0 fw-bold" style="font-size:1.5rem">2.º Dia — Sexta-feira, 12 de Junho de 2026</h2>
                <small class="text-muted"><i class="bi bi-geo-alt"></i> ISPM – Salas de Conferência, 31, 32, 33 e 34</small>
            </div>
        </div>

        {{-- Mini-cursos Dia 2 --}}
        <h4 class="prog-secao-titulo mb-3"><i class="bi bi-journal-bookmark-fill text-accent"></i> Mini-Cursos · 2.º Dia</h4>

        @if($miniCursos->has('2.º Dia · 12 Jun'))
            @php $mcDia2 = $miniCursos['2.º Dia · 12 Jun']->groupBy('hora'); @endphp
            @foreach($mcDia2 as $hora => $cursos)
            <div class="row g-3 mb-3">
                @foreach($cursos as $mc)
                <div class="col-md-6">
                    <div class="prog-block prog-block-minicurso h-100">
                        <div class="prog-time">{{ $mc->hora }}</div>
                        <div class="prog-content">
                            <div class="prog-tag prog-tag-mini">Mini-Curso</div>
                            <div class="prog-local"><i class="bi bi-geo-alt"></i> {{ $mc->local }}</div>
                            <div class="prog-titulo">{{ $mc->titulo }}</div>
                            <div class="prog-speaker"><i class="bi bi-person-fill"></i> {{ $mc->prelector }}</div>
                            <div class="prog-moderador"><i class="bi bi-mic"></i> Moderador: {{ $mc->moderador }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        @endif

        {{-- Comunicações Livres --}}
        <h4 class="prog-secao-titulo mt-4 mb-3"><i class="bi bi-file-earmark-text text-accent"></i> Comunicações Livres — 12h00 às 14h00</h4>

        @php
        $paineis = [
            [
                'numero'    => 'I',
                'titulo'    => 'Economia, Direito e Governação para o Desenvolvimento',
                'local'     => 'Sala 31',
                'moderador' => 'Vlade Marcial Tchiponda, MSc.',
                'cor'       => '#0f4c81',
                'comunicacoes' => [
                    ['hora'=>'12h00','prelector'=>'José Januário, PhD.',                                                                'tema'=>'As Percepções sobre Segurança e Impacto no Desenvolvimento do Turismo em Angola: visão, análise de percepção e proposta de Mitigação'],
                    ['hora'=>'12h20','prelector'=>'Jeremias Kabaco, Lic.',                                                              'tema'=>'Análise de Vulnerabilidade e Gestão de Incidentes da Segurança de Informação da Rede de Dados no ISPPU'],
                    ['hora'=>'12h40','prelector'=>'Agnaldo Gaspar, MSc.',                                                               'tema'=>'O Impacto da Comunicação Interna na Construção da Cultura Organizacional: Caso do Colégio BG0016 Raúl David — Catumbela'],
                    ['hora'=>'13h00','prelector'=>'Dilson Cupenala, Lic.',                                                              'tema'=>'Análise dos Direitos da Propriedade Intelectual face ao Crescente Uso da Inteligência Artificial'],
                    ['hora'=>'13h20','prelector'=>'Gamaliel Domingos, Estudante.',                                                      'tema'=>'Estudo para implementação, com foco em confinamento bovino na Província de Benguela'],
                    ['hora'=>'13h40','prelector'=>'Inovel Martines Varela, MSc. · Yensy Lazara Rodríguez Gonález, MSc. · Santa Caridad González Corrales, PhD.','tema'=>'O empreendedorismo universitário como alternativa para o desenvolvimento económico do Município de Menongue'],
                    ['hora'=>'14h00','prelector'=>'Laurinda Chilombo Franco, Estudante.',                                               'tema'=>'O papel da comunicação corporativa no fortalecimento da imagem empresarial: Estudo interactivo com os colaboradores da empresa Tombosy Lda'],
                ],
            ],
            [
                'numero'    => 'II',
                'titulo'    => 'Transformação Digital e Desenvolvimento Nacional',
                'local'     => 'Sala 32',
                'moderador' => 'Miguel dos Santos, Lic.',
                'cor'       => '#198754',
                'comunicacoes' => [
                    ['hora'=>'12h00','prelector'=>'Kizuva Tomas Garcia, Lic.',                                                          'tema'=>'Automação dos interruptores do Instituto Superior Politécnico Maravilha — Benguela'],
                    ['hora'=>'12h20','prelector'=>'Nsimba Nsinge, Lic.',                                                                'tema'=>'Uso das TIC\'s e IA das Organizações — O caso do Software Primavera'],
                    ['hora'=>'12h40','prelector'=>'Nilson Wahuluka · Palaça Cesar, MSc.',                                               'tema'=>'Utilização das TIC\'s na gestão integral dos riscos de desastres'],
                    ['hora'=>'13h00','prelector'=>'Nelson Augusto Japão, Estudante.',                                                   'tema'=>'Desenvolvimento de uma plataforma de monitorização de segurança no transporte interprovincial de passageiros em Angola'],
                    ['hora'=>'13h20','prelector'=>'José Honório Alberto Curiaquita, MSc.',                                              'tema'=>'O uso da tecnologia Power Line Communication (PLC) para transmissão de dados'],
                    ['hora'=>'13h40','prelector'=>'Yensy Lazara Rodríguez Gonález, MSc. · Inovel Martines Varela, MSc. · Santa Caridad González Corrales, PhD.','tema'=>'Uso da inteligência artificial no processo de investigação científica em estudantes do Curso de Comunicação Social do ISPPM'],
                    ['hora'=>'14h00','prelector'=>'David Conga Matombe, MSc.',                                                          'tema'=>'VOVA: desenvolvimento de uma plataforma web progressiva de acessibilidade para a comunidade surda em Angola'],
                ],
            ],
            [
                'numero'    => 'III',
                'titulo'    => 'Educação, Sociedade e Desenvolvimento Humano',
                'local'     => 'Sala 33',
                'moderador' => 'Orlando Alberto, MSc.',
                'cor'       => '#6f42c1',
                'comunicacoes' => [
                    ['hora'=>'12h00','prelector'=>'Kelson Chavonga, MSc.',                                                              'tema'=>'Governação e Desenvolvimento do capital humano em Angola: desafios e perspectivas contemporâneas'],
                    ['hora'=>'12h20','prelector'=>'Victor Saculanda, PhD.',                                                             'tema'=>'O tratamento didáctico da história local de Benguela em vinculação com a História de Angola na formação profissional pedagógica'],
                    ['hora'=>'12h40','prelector'=>'Victor Nungulu Pedro, Lic. · Joaquim Junior, Lic. · Solva Haiquela, Lic.',           'tema'=>'Fisioterapia Preventiva da Hipercifose aos alunos adolescentes da 5.ª classe do Complexo Escolar Juaquim Kapango'],
                    ['hora'=>'13h00','prelector'=>'Maireylis Rabelo Valdivia, PhD.',                                                    'tema'=>'A interdisciplinaridade como experiência metodológica para a aprendizagem no tópico de habilidades motoras básicas'],
                    ['hora'=>'13h20','prelector'=>'Lázaro Nganda Sapalo, Estudante.',                                                   'tema'=>'Fraude académica e suas implicações na vida prática e profissional dos estudantes'],
                    ['hora'=>'13h40','prelector'=>'Domingos Caginga Quinzeca, PhD.',                                                    'tema'=>'Avaliação do impacto do processo de superação no desempenho dos Professores do Instituto Superior Politécnico Maravilha'],
                    ['hora'=>'14h00','prelector'=>'Solange Judite Gomes Amado Jorge.',                                                  'tema'=>'O Ensino da História como fundamento da integração Africana: Desafios para a construção de uma unidade continental no Século XXI'],
                ],
            ],
            [
                'numero'    => 'IV',
                'titulo'    => 'Educação, Sociedade e Desenvolvimento Humano (cont.)',
                'local'     => 'Sala 34',
                'moderador' => 'Brigite Valaço, MSc.',
                'cor'       => '#dc6a00',
                'comunicacoes' => [
                    ['hora'=>'12h00','prelector'=>'Lorena Riveri Jimenes, MSc. · Rafael Torres Velazquez, MSc.',                        'tema'=>'Impacto das actividades desportivas recreativas no desenvolvimento dos jovens nas comunidades de Benguela'],
                    ['hora'=>'12h20','prelector'=>'Adelino Tchendohamba Tchimbingo, MSc.',                                              'tema'=>'Escrita académica como instrumento de produção e valorização do conhecimento'],
                    ['hora'=>'12h40','prelector'=>'Manuel Kandingolo Samuti, Estudante.',                                               'tema'=>'Desafios da inclusão e equidade no ensino superior angolano: estudo com estudantes do 2.º ano na especialidade de Biologia do ISPM'],
                    ['hora'=>'13h00','prelector'=>'Eduardo André Hólwa, Estudante.',                                                    'tema'=>'Influência dos recursos didácticos visuais na motivação para o estudo de histologia'],
                    ['hora'=>'13h20','prelector'=>'Luciano Kambili Sacutala, Lic.',                                                     'tema'=>'A constatação de histórias e o uso da IA como estratégias psicopedagógicas no apoio emocional de crianças sinistradas das enchentes do rio Cavaco'],
                    ['hora'=>'13h40','prelector'=>'José Pedro da Cruz, PhD. · José Muenho, PhD.',                                       'tema'=>'As comemorações do 4 de Fevereiro e a trajectória da estrutura económica e social angolana: abordagem sociológica e transdisciplinar'],
                    ['hora'=>'14h00','prelector'=>'Iracelma Simão, MSc.',                                                               'tema'=>'A análise da qualidade da água distribuída pela empresa Pública da Água e Saneamento de Benguela'],
                ],
            ],
        ];
        @endphp

        <div class="row g-4">
            @foreach($paineis as $painel)
            <div class="col-lg-6">
                <div class="prog-painel" style="--painel-cor: {{ $painel['cor'] }}">
                    <div class="prog-painel-header">
                        <span class="prog-painel-num">Painel {{ $painel['numero'] }}</span>
                        <div class="prog-painel-titulo">{{ $painel['titulo'] }}</div>
                        <div class="prog-painel-meta">
                            <span><i class="bi bi-geo-alt"></i> {{ $painel['local'] }}</span>
                            <span><i class="bi bi-mic"></i> Moderador: {{ $painel['moderador'] }}</span>
                        </div>
                    </div>
                    <div class="prog-painel-body">
                        @foreach($painel['comunicacoes'] as $com)
                        <div class="prog-com">
                            <span class="prog-com-hora">{{ $com['hora'] }}</span>
                            <div>
                                <div class="prog-com-tema">{{ $com['tema'] }}</div>
                                <div class="prog-com-prelector"><i class="bi bi-person"></i> {{ $com['prelector'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Encerramento Dia 2 --}}
        <div class="prog-block prog-block-cerimonia mt-4">
            <div class="prog-time">15h00</div>
            <div class="prog-content">
                <div class="prog-tag">Sessão de Encerramento</div>
                <div class="prog-titulo">Encerramento da XI Jornada Científica do ISPM</div>
                <ul class="prog-list">
                    <li>Momento Cultural</li>
                    <li>Discurso de encerramento</li>
                    <li>Entrega de certificados de participação</li>
                </ul>
                <div class="prog-local"><i class="bi bi-geo-alt"></i> Sala de Conferência</div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('head')
<style>
    /* ---- Programa Layout ---- */
    .prog-day-badge {
        width: 48px; height: 48px; border-radius: 50%;
        background: var(--color-primary, #0f4c81);
        color: #fff; font-size: 1.4rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .prog-secao-titulo {
        font-size: 1.1rem; font-weight: 700;
        border-bottom: 2px solid var(--color-primary, #0f4c81);
        padding-bottom: .4rem;
    }
    .prog-horario-label {
        font-size: .85rem; font-weight: 600; color: var(--color-primary,#0f4c81);
        background: #eef3fa; display: inline-block;
        padding: .2rem .7rem; border-radius: 1rem;
    }

    /* ---- Blocos de programa ---- */
    .prog-block {
        display: flex; gap: 1rem; align-items: flex-start;
        padding: .9rem 1rem; border-radius: .6rem;
        border-left: 4px solid transparent;
        background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }
    .prog-block-cerimonia  { border-left-color: var(--color-primary,#0f4c81); }
    .prog-block-magistral  { border-left-color: #f37021; background: #fff8f3; }
    .prog-block-cultural   { border-left-color: #6f42c1; }
    .prog-block-pausa      { border-left-color: #adb5bd; background: #f8f9fa; }
    .prog-block-minicurso  { border-left-color: #198754; }

    .prog-time {
        font-size: .78rem; font-weight: 700; color: var(--color-primary,#0f4c81);
        min-width: 90px; padding-top: 2px; line-height: 1.3;
    }
    .prog-content { flex: 1; }
    .prog-tag {
        display: inline-block; font-size: .68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        padding: .1rem .5rem; border-radius: 1rem;
        background: #e9ecef; color: #495057; margin-bottom: .3rem;
    }
    .prog-tag-magistral { background: #f37021; color: #fff; }
    .prog-tag-mini      { background: #198754; color: #fff; }
    .prog-titulo  { font-weight: 600; line-height: 1.4; font-size: .95rem; }
    .prog-speaker { font-size: .82rem; color: #495057; margin-top: .25rem; }
    .prog-moderador { font-size: .80rem; color: #6c757d; margin-top: .2rem; }
    .prog-local   { font-size: .80rem; color: #6c757d; margin-bottom: .2rem; }
    .prog-list    { font-size: .85rem; margin: .4rem 0 0 0; padding-left: 1.2rem; color: #495057; }

    /* ---- Painéis Comunicações ---- */
    .prog-painel {
        border: 1px solid #dee2e6; border-radius: .75rem;
        overflow: hidden; height: 100%;
    }
    .prog-painel-header {
        background: var(--painel-cor);
        padding: .9rem 1rem;
        color: #fff;
    }
    .prog-painel-num  { font-size: .7rem; font-weight: 700; text-transform: uppercase; opacity: .8; }
    .prog-painel-titulo { font-weight: 700; line-height: 1.3; font-size: .95rem; margin-top: .15rem; }
    .prog-painel-meta { font-size: .75rem; opacity: .85; margin-top: .4rem; display: flex; flex-wrap: wrap; gap: .6rem; }
    .prog-painel-body { padding: .5rem 0; }

    .prog-com {
        display: flex; gap: .7rem; align-items: flex-start;
        padding: .55rem 1rem; border-bottom: 1px solid #f0f0f0;
    }
    .prog-com:last-child { border-bottom: none; }
    .prog-com-hora    { font-size: .72rem; font-weight: 700; color: var(--painel-cor); min-width: 42px; padding-top: 2px; }
    .prog-com-tema    { font-size: .83rem; font-weight: 500; line-height: 1.35; }
    .prog-com-prelector { font-size: .75rem; color: #6c757d; margin-top: .15rem; }
</style>
@endpush
