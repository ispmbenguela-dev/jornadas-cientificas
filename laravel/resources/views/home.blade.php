@extends('layouts.app')

@section('title', 'XI Jornada Científico-Metodológica Geral — ISPM')

@section('content')
    @if (!empty($branding['banner']))
        <div class="hero-banner-image">
            <img src="{{ $branding['banner'] }}" alt="XI Jornada Científico-Metodológica — ISPM · 11 e 12 de Junho de 2026" />
        </div>
    @endif

    <header id="evento" class="landing-hero">
        <div class="hero-bg" aria-hidden="true"></div>
        <div class="hero-grid" aria-hidden="true"></div>

        <div class="container landing-hero-inner">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="hero-badges mb-3">
                        <span class="hbui-badge hbui-badge-warning">
                            <span class="dot"></span> Inscrições abertas
                        </span>
                        <span class="hbui-badge hbui-badge-outline">
                            <i class="bi bi-calendar-event"></i> 11 e 12 de Junho · 2026
                        </span>
                        <span class="hbui-badge hbui-badge-outline-soft">
                            <i class="bi bi-geo-alt"></i> ISPM · Benguela
                        </span>
                    </div>

                    <h1 class="hero-title">
                        <span class="text-accent">XI Jornada</span>
                        Científico-Metodológica Geral
                        <span class="hero-sub">
                            Instituto Superior Politécnico Maravilha — ISPM
                        </span>
                    </h1>

                    <p class="hero-lead">
                        Dois dias dedicados à investigação, ao ensino superior e ao
                        papel da ciência no progresso de Angola. Aberto a docentes,
                        estudantes e ao público em geral.
                    </p>

                    <div class="lema-card">
                        <span class="lema-label"><i class="bi bi-quote"></i> Lema da edição</span>
                        <p>
                            <strong>“O Ensino Superior e os desafios do Desenvolvimento
                            Económico e Social de Angola”</strong>
                            <span class="lema-flag" aria-label="Angola">🇦🇴</span>
                        </p>
                    </div>
                    <br>
                    <div class="hero-actions">
                        <a href="{{ route('inscricao.create') }}" class="btn btn-cta btn-lg">
                            <i class="bi bi-pencil-square"></i> Fazer Inscrição
                        </a>
                        <a href="{{ route('submissao.create') }}" class="btn btn-ghost btn-lg">
                            <i class="bi bi-file-earmark-text"></i> Submeter Trabalho
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="event-card">
                        <div class="event-card-head">
                            <span class="hbui-badge hbui-badge-default">
                                <i class="bi bi-stars"></i> Próximo evento
                            </span>
                        </div>

                        <div class="event-status">
                            <div class="event-status-icon">
                                <i class="bi bi-calendar2-event-fill"></i>
                            </div>
                            <div>
                                <h3>11 — 12 Junho 2026</h3>
                                <p>Quinta e Sexta-feira</p>
                            </div>
                        </div>

                        <hr />

                        <ul class="ended-list">
                            <li>
                                <span class="ended-key">Edição</span>
                                <span class="ended-val">XI Jornada Científico-Metodológica</span>
                            </li>
                            <li>
                                <span class="ended-key">Local</span>
                                <span class="ended-val">ISPM · Av. Aires de Almeida Santos</span>
                            </li>
                            <li>
                                <span class="ended-key">Inscrições</span>
                                <span class="ended-val">18 Mai — 05 Jun 2026</span>
                            </li>
                            <li>
                                <span class="ended-key">Submissão</span>
                                <span class="ended-val">
                                    @if ($submissaoAberta)
                                        até {{ $submissaoPrazo->translatedFormat('d \d\e M Y') }}
                                    @else
                                        encerrada
                                    @endif
                                </span>
                            </li>
                        </ul>

                        <a href="{{ route('inscricao.create') }}" class="btn btn-cta w-100">
                            <i class="bi bi-arrow-right-circle"></i> Inscrever agora
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="requisitos" class="section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Submissão de trabalhos</span>
                <h2 class="section-title">
                    Requisitos para <span class="text-accent">apresentação</span>
                </h2>
                <p class="section-lead mx-auto" style="max-width: 760px">
                    Os artigos científicos devem ter no máximo <strong>10 páginas</strong>,
                    com espaçamento duplo, margens de 2.5&nbsp;cm, fonte Arial ou
                    Times New Roman tamanho 12, em formato Word A4, com referências
                    segundo as Normas APA (7.ª Edição).
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="req-card">
                        <div class="req-icon"><i class="bi bi-file-earmark-word"></i></div>
                        <h4>Formatação</h4>
                        <ul>
                            <li>Máx. 10 páginas</li>
                            <li>Espaçamento duplo</li>
                            <li>Margens 2.5&nbsp;cm</li>
                            <li>A4 · Word</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="req-card">
                        <div class="req-icon"><i class="bi bi-type"></i></div>
                        <h4>Tipografia</h4>
                        <ul>
                            <li>Arial ou Times New Roman</li>
                            <li>Tamanho 12</li>
                            <li>Referências APA 7.ª Ed.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="req-card">
                        <div class="req-icon"><i class="bi bi-list-check"></i></div>
                        <h4>Estrutura</h4>
                        <ul>
                            <li>Cabeçalho da XI Jornada</li>
                            <li>Título em maiúsculas</li>
                            <li>Autores (principal sublinhado)</li>
                            <li>Instituição e e-mail</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="req-card">
                        <div class="req-icon"><i class="bi bi-journal-text"></i></div>
                        <h4>Conteúdo</h4>
                        <ul>
                            <li>Resumo (até 250 palavras)</li>
                            <li>Introdução e desenvolvimento</li>
                            <li>Metodologia e resultados</li>
                            <li>Conclusões e bibliografia</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('submissao.create') }}" class="btn btn-cta btn-lg">
                    <i class="bi bi-cloud-upload"></i> Submeter o meu trabalho
                </a>
            </div>
        </div>
    </section>

    <section id="cronograma" class="section section-alt">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Datas importantes</span>
                <h2 class="section-title">
                    Cronograma <span class="text-accent">da XI Jornada</span>
                </h2>
                <p class="section-lead mx-auto" style="max-width: 720px">
                    Anote no calendário os marcos da edição de 2026 — desde a submissão
                    de resumos até ao evento.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="schedule-card">
                        <span class="schedule-step">01</span>
                        <h5><i class="bi bi-file-earmark-plus"></i> Entrega de resumos</h5>
                        <p class="schedule-date">
                            @if ($submissaoAberta)
                                Até {{ $submissaoPrazo->translatedFormat('d \d\e F · Y') }}
                            @else
                                Encerrado em {{ $submissaoPrazo->translatedFormat('d \d\e F · Y') }}
                            @endif
                        </p>
                        <p class="schedule-desc">Submissão de resumos e resultados de investigações científicas.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="schedule-card">
                        <span class="schedule-step">02</span>
                        <h5><i class="bi bi-pencil-square"></i> Inscrições</h5>
                        <p class="schedule-date">18 de Maio — 05 de Junho · 2026</p>
                        <p class="schedule-desc">Período oficial de inscrição para participantes e expositores.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="schedule-card">
                        <span class="schedule-step">03</span>
                        <h5><i class="bi bi-megaphone"></i> Trabalhos admitidos</h5>
                        <p class="schedule-date">18 — 30 de Maio · 2026</p>
                        <p class="schedule-desc">Publicação da lista dos trabalhos admitidos para apresentação.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="schedule-card schedule-card-hl">
                        <span class="schedule-step">04</span>
                        <h5><i class="bi bi-mortarboard"></i> Realização do evento</h5>
                        <p class="schedule-date">11 e 12 de Junho · 2026</p>
                        <p class="schedule-desc">Dois dias de comunicações, mini-cursos e debate científico.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="programa" class="section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Agenda do evento</span>
                <h2 class="section-title">
                    Programa da <span class="text-accent">XI Jornada</span>
                </h2>
                <p class="section-lead mx-auto" style="max-width: 760px">
                    Dois dias de conferência magistral, mini-cursos e comunicações livres
                    em três painéis temáticos. Consulte abaixo cada momento por dia.
                </p>
            </div>

            <ul class="nav nav-pills justify-content-center mb-5 prog-tabs" id="programTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="dia1-tab" data-bs-toggle="pill"
                            data-bs-target="#dia1" type="button" role="tab">
                        <i class="bi bi-calendar2-day"></i>
                        <span>1.º Dia · <strong>11 Jun</strong> · Quinta-feira</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dia2-tab" data-bs-toggle="pill"
                            data-bs-target="#dia2" type="button" role="tab">
                        <i class="bi bi-calendar2-day"></i>
                        <span>2.º Dia · <strong>12 Jun</strong> · Sexta-feira</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                {{-- DIA 1 --}}
                <div class="tab-pane fade show active" id="dia1" role="tabpanel">

                    <h3 class="prog-block-title">
                        <i class="bi bi-flag-fill"></i> Sessão de Abertura
                        <small>Sala de Conferência</small>
                    </h3>

                    <div class="timeline mb-5">
                        <div class="timeline-item">
                            <div class="timeline-time">08h30</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-outline">Protocolo</span>
                                <h4>Chegada e confirmação dos convidados</h4>
                                <p><i class="bi bi-geo-alt"></i> Sala de Conferência</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-time">09h00</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-default">Abertura</span>
                                <h4>Abertura oficial do evento</h4>
                                <p><i class="bi bi-music-note-beamed"></i> Entoação do Hino Nacional seguida de um minuto de silêncio</p>
                                <p><i class="bi bi-people"></i> Apresentação da mesa de presidium e das entidades académicas e governamentais</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-time">09h45</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-secondary">Discurso</span>
                                <h4>Discurso de abertura</h4>
                                <p><i class="bi bi-person-badge"></i> <strong>Presidente do ISPM — Benguela</strong></p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-time">10h00</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-warning">Cultural</span>
                                <h4>Momento cultural</h4>
                            </div>
                        </div>

                        <div class="timeline-item highlight">
                            <div class="timeline-time">10h10</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-success">
                                    <i class="bi bi-stars"></i> Conferência Magistral
                                </span>
                                <h4>“O Ensino Superior e o Desenvolvimento em Angola: Desafios e Oportunidades”</h4>
                                <p>
                                    <i class="bi bi-person-badge"></i>
                                    <strong>Prof.ª Cat. Maria da Conceição Barbosa Mendes</strong> —
                                    Presidente do Instituto Superior de Ciências da Educação de Benguela
                                </p>
                            </div>
                        </div>
                    </div>

                    <h3 class="prog-block-title">
                        <i class="bi bi-easel2-fill"></i> Mini-Cursos · 1.º Bloco
                        <small>13h30 – 15h00</small>
                    </h3>

                    <div class="row g-4 mb-5">
                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">13h30</span>
                                    <span class="hbui-badge hbui-badge-room">Sala de Conferência</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">IA · Educação</span>
                                </header>
                                <h4 class="talk-title">
                                    Inteligência artificial como ferramenta de inclusão educativa: potencialidade e limites
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Sérgio Cespo Coelho da Silva Pinto · Prof. associado, Univ. Fluminense (Brasil)</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Kelson Chavonga, MSc.</li>
                                </ul>
                            </article>
                        </div>

                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">13h30</span>
                                    <span class="hbui-badge hbui-badge-room">Sala 33</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">Saúde & Higiene</span>
                                </header>
                                <h4 class="talk-title">
                                    Segurança, Saúde e Higiene no Trabalho — Nível 1
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> José Mulangue, PhD</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Luís Gomes, Lic.</li>
                                </ul>
                            </article>
                        </div>
                    </div>

                    <h3 class="prog-block-title">
                        <i class="bi bi-easel2-fill"></i> Mini-Cursos · 2.º Bloco
                        <small>15h00 – 16h30</small>
                    </h3>

                    <div class="row g-4 mb-4">
                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">15h00</span>
                                    <span class="hbui-badge hbui-badge-room">Sala de Conferência</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">IA Generativa</span>
                                </header>
                                <h4 class="talk-title">
                                    Inteligência artificial generativa: ferramenta para a pesquisa científica
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Ilma Rodrigues de Souza Fausto · Prof. associada, Univ. Fluminense (Brasil)</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Domingos Barbante, MSc.</li>
                                </ul>
                            </article>
                        </div>

                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">15h00</span>
                                    <span class="hbui-badge hbui-badge-room">Sala 32</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">Capital Humano</span>
                                </header>
                                <h4 class="talk-title">
                                    Importância do Qualificador Ocupacional na Gestão do Capital Humano à luz do Decreto Presidencial 96/22, de 2 de Maio
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Gaudêncio Félix, MSc.</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Eunice Pedro, Lic.</li>
                                </ul>
                            </article>
                        </div>
                    </div>

                    <h3 class="prog-block-title">
                        <i class="bi bi-easel2-fill"></i> Mini-Curso · 3.º Bloco
                        <small>16h00 – 18h00</small>
                    </h3>

                    <div class="row g-4 mb-4">
                        <div class="col-lg-8 mx-auto">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">16h00</span>
                                    <span class="hbui-badge hbui-badge-room">Sala de Conferência</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">Investigação</span>
                                </header>
                                <h4 class="talk-title">
                                    Elaboração de projectos de investigação para captação de fontes de financiamento
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Arnaldo Faustino, PhD</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Kelson Chavonga, MSc.</li>
                                </ul>
                            </article>
                        </div>
                    </div>

                    <div class="prog-closing">
                        <i class="bi bi-flag-fill"></i>
                        <span><strong>18h00</strong> · Encerramento do 1.º dia</span>
                    </div>
                </div>

                {{-- DIA 2 --}}
                <div class="tab-pane fade" id="dia2" role="tabpanel">

                    <h3 class="prog-block-title">
                        <i class="bi bi-easel2-fill"></i> Mini-Cursos
                        <small>08h30 – 11h30</small>
                    </h3>

                    <div class="row g-4 mb-5">
                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">08h30 – 10h00</span>
                                    <span class="hbui-badge hbui-badge-room">Sala de Conferência</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">Educação Inclusiva</span>
                                </header>
                                <h4 class="talk-title">
                                    Educação inclusiva no século XXI: estratégias para reduzir desigualdades educacionais em Angola
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Ruth Mariani Braz · Prof. associada, Univ. Fluminense (Brasil)</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Iracelma Simão Neto Afonso, MSc.</li>
                                </ul>
                            </article>
                        </div>
                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">10h00 – 11h30</span>
                                    <span class="hbui-badge hbui-badge-room">Sala 33</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">Gestão da Qualidade</span>
                                </header>
                                <h4 class="talk-title">
                                    A Avaliação Institucional: Desafios para a Melhoria da Qualidade do Desempenho do Instituto Superior Politécnico Maravilha
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Justo Che Soler, PhD.</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Ernesto Kambuangue, Lic.</li>
                                </ul>
                            </article>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <span class="section-eyebrow">Comunicações Livres</span>
                        <h3 class="prog-section-title">
                            Três painéis temáticos em <span class="text-accent">paralelo</span>
                        </h3>
                    </div>

                    {{-- PAINEL I --}}
                    <div class="battery-block">
                        <div class="battery-head">
                            <div>
                                <span class="hbui-badge hbui-badge-default">Painel I</span>
                                <h3 class="battery-title">Economia, Direito e Governação para o Desenvolvimento</h3>
                            </div>
                            <span class="battery-count">Sala 31</span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Segurança Informática</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Análise de vulnerabilidade e gestão de incidentes da segurança de informação da rede de dados no ISPPU
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Jeremias Kabaco, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Milton Sessa, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Cultura Organizacional</span>
                                    </header>
                                    <h4 class="talk-title">
                                        O impacto da comunicação interna na construção da cultura organizacional — caso do Colégio BG0016 Raúl David, Catumbela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Agnaldo Gaspar, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Teresa Bamo, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Propriedade Intelectual</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Análise dos direitos da propriedade intelectual face ao crescente uso da inteligência artificial
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Dilson Cupenala, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Miracelma Barroso, Lic.</li>
                                    </ul>
                                </article>
                            </div>
                        </div>
                    </div>

                    {{-- PAINEL II --}}
                    <div class="battery-block">
                        <div class="battery-head">
                            <div>
                                <span class="hbui-badge hbui-badge-default">Painel II</span>
                                <h3 class="battery-title">Transformação Digital e Desenvolvimento Nacional</h3>
                            </div>
                            <span class="battery-count">Sala 32</span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Agro-Indústria</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Frigorífico Agro-J, Lda: estudo para implementação com foco em confinamento bovino na Província de Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Gamaliel Domingos · Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Clemente Luzolo, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">TIC & IA</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Uso das TIC e IA nas organizações — o caso do software Primavera
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Nsimba Nsinge, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Eunice Pedro, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Fintech</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Digitalização dos sistemas de pagamento no Ensino Superior em Angola — abordagem baseada na plataforma INTELIZE
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Eduardo Cavungo, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Desidério Sessa, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Automação</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Automação dos interruptores do Instituto Superior Politécnico Maravilha — Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Paulo Ricardo, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Honório Kuriaquita, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Aplicação Web</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Aplicação web para avaliação de desempenho e qualidade docente no Instituto Superior Politécnico Maravilha
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Kisuva Garcia, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Santos Viana, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">IA · RH</span>
                                    </header>
                                    <h4 class="talk-title">
                                        A inteligência artificial no processo de recrutamento e selecção de pessoas
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Cairo Isaac · Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Bregel Sachilunga, Lic.</li>
                                    </ul>
                                </article>
                            </div>
                        </div>
                    </div>

                    {{-- PAINEL III --}}
                    <div class="battery-block">
                        <div class="battery-head">
                            <div>
                                <span class="hbui-badge hbui-badge-default">Painel III</span>
                                <h3 class="battery-title">Educação, Sociedade e Desenvolvimento Humano</h3>
                            </div>
                            <span class="battery-count">Sala 33</span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Gestão Pública</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Modelos de gestão de pessoas por competências no sector público e implicações na valorização do capital humano — Direcção Municipal da Educação de Benguela (2022/2023)
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> João Ivo Martins, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valasco, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Governação</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Governação e desenvolvimento do capital humano em Angola: desafios e perspectivas contemporâneas
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Kelsom Chivonga</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valasco, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">11h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Fisioterapia</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Fisioterapia preventiva da hipercifose em alunos adolescentes da 5.ª classe — Complexo Escolar Joaquim Kapango, Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectores:</strong> Victor Nungulu Pedro, Lic. · Joaquim Júnior, Lic. · Solva Haiquela, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valasco, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Escrita Académica</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Escrita académica como instrumento de produção e valorização do conhecimento
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Adelino Tchendohamba Tchimbingo</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valasco, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Metodologia</span>
                                    </header>
                                    <h4 class="talk-title">
                                        A interdisciplinaridade como experiência metodológica na aprendizagem das habilidades motoras básicas — Ginástica Básica
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Maireylis Rabelo Valdivia, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valasco, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Ambiente</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Análise da qualidade da água distribuída pela Empresa Pública de Água e Saneamento de Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Iracelma Simão, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valasco, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Desporto</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Impacto das actividades desportivas recreativas no desenvolvimento dos jovens nas comunidades de Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectores:</strong> Lorena Riveri Jimenes, MSc. · Rafael Torres Velazquez, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valasco, Lic.</li>
                                    </ul>
                                </article>
                            </div>
                        </div>
                    </div>

                    <h3 class="prog-block-title">
                        <i class="bi bi-flag-fill"></i> Encerramento da Jornada
                        <small>Sala de Conferência</small>
                    </h3>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-time">16h00</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-secondary">Discurso</span>
                                <h4>Discurso de encerramento da Jornada</h4>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-time">—</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-warning">Cultural</span>
                                <h4>Momento cultural e entrega de certificados aos participantes</h4>
                            </div>
                        </div>

                        <div class="timeline-item highlight">
                            <div class="timeline-time">17h30</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-destructive">Final</span>
                                <h4>Encerramento da actividade</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="inscricao" class="section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Taxas e pagamento</span>
                <h2 class="section-title">
                    Como fazer a sua <span class="text-accent">inscrição</span>
                </h2>
                <p class="section-lead mx-auto" style="max-width: 720px">
                    Escolha a sua categoria, efectue o depósito ou transferência e
                    envie o comprovativo através do formulário online.
                </p>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-7">
                    <div class="fees-card">
                        <h4 class="fees-title">
                            <i class="bi bi-credit-card-2-front"></i> Taxas de inscrição
                        </h4>
                        <p class="fees-sub">Valores em Kwanzas · Participação / Mini-Curso</p>

                        <div class="fees-table">
                            <div class="fees-row fees-row-head">
                                <span>Categoria</span>
                                <span>Participação</span>
                                <span>Mini-Curso</span>
                            </div>
                            <div class="fees-row">
                                <span><i class="bi bi-person-workspace"></i> Docentes</span>
                                <span class="fees-amount">10.000,00 Kz</span>
                                <span class="fees-amount fees-amount-alt">5.000,00 Kz</span>
                            </div>
                            <div class="fees-row">
                                <span><i class="bi bi-mortarboard"></i> Estudantes</span>
                                <span class="fees-amount">2.000,00 Kz</span>
                                <span class="fees-amount fees-amount-alt">3.000,00 Kz</span>
                            </div>
                            <div class="fees-row">
                                <span><i class="bi bi-people"></i> Público em geral</span>
                                <span class="fees-amount">10.000,00 Kz</span>
                                <span class="fees-amount fees-amount-alt">5.000,00 Kz</span>
                            </div>
                            <div class="fees-row">
                                <span><i class="bi bi-person-badge"></i> Pessoal Técnico Admin.</span>
                                <span class="fees-amount">2.000,00 Kz</span>
                                <span class="fees-amount fees-amount-alt">3.000,00 Kz</span>
                            </div>
                        </div>

                        <small class="fees-foot d-block mt-2">
                            <i class="bi bi-info-circle"></i> Pessoal Técnico Administrativo (PTA) deve apresentar o passe de funcionário no acto da inscrição.
                        </small>

                        <a href="{{ route('inscricao.create') }}" class="btn btn-cta mt-3">
                            <i class="bi bi-pencil-square"></i> Iniciar inscrição
                        </a>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bank-card">
                        <span class="hbui-badge hbui-badge-outline-soft mb-3">
                            <i class="bi bi-bank"></i> Dados bancários
                        </span>
                        <h4 class="bank-title">Depósito ou transferência</h4>

                        <ul class="bank-list">
                            <li>
                                <span class="bank-key">Banco</span>
                                <span class="bank-val">BPC</span>
                            </li>
                            <li>
                                <span class="bank-key">IBAN</span>
                                <span class="bank-val bank-iban">0010.0455.0165.8843.0116.9</span>
                            </li>
                            <li>
                                <span class="bank-key">Beneficiário</span>
                                <span class="bank-val">Instituto Superior Politécnico Maravilha</span>
                            </li>
                        </ul>

                        <a href="{{ route('inscricao.create') }}" class="btn btn-cta w-100">
                            <i class="bi bi-upload"></i> Enviar comprovativo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contactos" class="section section-alt">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Fale connosco</span>
                <h2 class="section-title">
                    Contactos da <span class="text-accent">organização</span>
                </h2>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-telephone"></i></div>
                        <h6>Comissão Científica</h6>
                        <p>
                            <a href="tel:+244922606147">922 606 147</a><br />
                            <a href="tel:+244957360076">957 360 076</a><br />
                            <a href="tel:+244922140990">922 140 990</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-envelope"></i></div>
                        <h6>E-mail Científico</h6>
                        <p>
                            <a href="mailto:vp.cientifica@ispmaravilha.com">vp.cientifica@ispmaravilha.com</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-building"></i></div>
                        <h6>Direcção do ISPM</h6>
                        <p>
                            <a href="tel:+244946081244">+244 946 081 244</a><br />
                            <a href="tel:+244955879021">+244 955 879 021</a><br />
                            <a href="mailto:ispm.direccao@hotmail.com">presidencia@ispmaravilha.com</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
                        <h6>Localização</h6>
                        <p>
                            Av. Aires de Almeida Santos, Nº 58<br />
                            <a href="https://www.ispmaravilha.com" target="_blank" rel="noopener">
                                www.ispmaravilha.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="next-edition-card">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="hbui-badge hbui-badge-secondary mb-3">
                            <i class="bi bi-archive"></i> Edição anterior
                        </span>
                        <h2 class="section-title m-0">
                            III Jornadas Científicas — <span class="text-accent">arquivo</span>
                        </h2>
                        <p class="section-lead mt-3">
                            Consulte o programa completo da edição anterior, realizada
                            a 08 de Maio de 2026, com 18 comunicações em 5 salas
                            paralelas.
                        </p>
                        <a href="{{ route('programa') }}" class="btn btn-cta">
                            <i class="bi bi-journal-text"></i> Ver Programa III Edição
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <div class="countdown-card">
                            <span class="countdown-label">Participe e contribua</span>
                            <div class="countdown-icon">
                                <i class="bi bi-stars"></i>
                            </div>
                            <h4>Fortaleça a investigação em Angola</h4>
                            <p>Junte-se a docentes, estudantes e investigadores na XI Jornada.</p>
                            <a href="{{ route('inscricao.create') }}" class="btn btn-cta w-100">
                                <i class="bi bi-pencil-square"></i> Quero participar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
