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
                        <i class="bi bi-easel2-fill"></i> Mini-Cursos de Abertura
                        <small>08h30 – 11h30</small>
                    </h3>

                    <div class="row g-4 mb-5">
                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">08h30</span>
                                    <span class="hbui-badge hbui-badge-room">Sala de Conferência</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">Educação Inclusiva</span>
                                </header>
                                <h4 class="talk-title">
                                    Educação inclusiva no século XXI: estratégias para reduzir desigualdades educacionais em Angola
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Ruth Mariani Braz · Prof. associada, Univ. Fluminense (Brasil)</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Iracelma Simão Neto Afonso, MSc.</li>
                                </ul>
                            </article>
                        </div>
                        <div class="col-lg-6">
                            <article class="talk-card">
                                <header>
                                    <span class="hbui-badge hbui-badge-default">10h00</span>
                                    <span class="hbui-badge hbui-badge-room">Sala 33</span>
                                    <span class="hbui-badge hbui-badge-outline-soft">Avaliação Institucional</span>
                                </header>
                                <h4 class="talk-title">
                                    A Avaliação Institucional: Desafios para a Melhoria da Qualidade do Desempenho do Instituto Superior Politécnico Maravilha
                                </h4>
                                <ul class="talk-meta">
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Justo Che Soler, PhD</li>
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Ernesto Kambuangue, Lic.</li>
                                </ul>
                            </article>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <span class="section-eyebrow">Comunicações Livres</span>
                        <h3 class="prog-section-title">
                            Quatro salas em <span class="text-accent">paralelo</span>
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
                                        <span class="hbui-badge hbui-badge-default">12h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Turismo & Segurança</span>
                                    </header>
                                    <h4 class="talk-title">
                                        As percepções sobre segurança e impacto no desenvolvimento do turismo em Angola: visão, análise de percepção e proposta de mitigação
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> José Januário, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Vlade Marcial Tchiponda, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Segurança Informática</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Análise de vulnerabilidade e gestão de incidentes da segurança de informação da rede de dados no ISPPU
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Jeremias Kabaco, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Vlade Marcial Tchiponda, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Cultura Organizacional</span>
                                    </header>
                                    <h4 class="talk-title">
                                        O impacto da comunicação interna na construção da cultura organizacional — caso do Colégio BG0016 Raúl David, Catumbela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Agnaldo Gaspar, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Vlade Marcial Tchiponda, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Propriedade Intelectual</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Análise dos direitos da propriedade intelectual face ao crescente uso da inteligência artificial
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Dilson Cupenala, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Vlade Marcial Tchiponda, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Agro-Indústria</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Estudo para implementação com foco em confinamento bovino na Província de Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Gamaliel Domingos, Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Vlade Marcial Tchiponda, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Empreendedorismo</span>
                                    </header>
                                    <h4 class="talk-title">
                                        O empreendedorismo universitário como alternativa para o desenvolvimento económico do Município de Menongue, Província do Cubango — proposta a partir do ISPPM
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectores:</strong> Inovel Martines Varela, MSc. · Yensy Lazara Rodríguez Gonález, MSc. · Santa Caridad González Corrales, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Vlade Marcial Tchiponda, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Comunicação Corporativa</span>
                                    </header>
                                    <h4 class="talk-title">
                                        O papel da comunicação corporativa no fortalecimento da imagem empresarial: estudo interactivo com os colaboradores da empresa Tombosy Lda
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Laurinda Chilombo Franco, Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Vlade Marcial Tchiponda, MSc.</li>
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
                                        <span class="hbui-badge hbui-badge-default">12h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Automação</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Automação dos interruptores do Instituto Superior Politécnico Maravilha — Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Kizuva Tomas Garcia, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Miguel dos Santos, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">TIC & IA</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Uso das TIC e IA nas organizações — o caso do software Primavera
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Nsimba Nsinge, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Miguel dos Santos, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Gestão de Riscos</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Utilização das TIC na gestão integral dos riscos de desastres
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Nilson Wahuluka · Palaça Cesar, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Miguel dos Santos, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Transportes & Segurança</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Desenvolvimento de uma plataforma de monitorização de segurança no transporte interprovincial de passageiros em Angola
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Nelson Augusto Japão, Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Miguel dos Santos, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Telecomunicações</span>
                                    </header>
                                    <h4 class="talk-title">
                                        O uso da tecnologia Power Line Communication (PLC) para transmissão de dados
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> José Honório Alberto Curiaquita, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Miguel dos Santos, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">IA · Investigação</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Uso da inteligência artificial no processo de investigação científica em estudantes do Curso de Comunicação Social do ISPPM
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectores:</strong> Yensy Lazara Rodríguez Gonález, MSc. · Inovel Martines Varela, MSc. · Santa Caridad González Corrales, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Miguel dos Santos, Lic.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Acessibilidade Digital</span>
                                    </header>
                                    <h4 class="talk-title">
                                        VOVA: desenvolvimento de uma plataforma web progressiva de acessibilidade para a comunidade surda em Angola
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> David Conga Matombe, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Miguel dos Santos, Lic.</li>
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
                                        <span class="hbui-badge hbui-badge-default">12h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Governação</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Governação e desenvolvimento do capital humano em Angola: desafios e perspectivas contemporâneas nos domínios da educação, ciência, tecnologia, saúde e bem-estar social
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Kelson Chavonga, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Orlando Alberto, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">História Local</span>
                                    </header>
                                    <h4 class="talk-title">
                                        O tratamento didáctico da história local de Benguela em vinculação com a história de Angola na formação profissional pedagógica: metodologia para a sua implementação
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Victor Saculanda, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Orlando Alberto, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Fisioterapia</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Fisioterapia preventiva da hipercifose em alunos adolescentes da 5.ª classe — Complexo Escolar Joaquim Kapango, Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectores:</strong> Victor Nungulu Pedro, Lic. · Joaquim Júnior, Lic. · Solva Haiquela, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Orlando Alberto, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Metodologia</span>
                                    </header>
                                    <h4 class="talk-title">
                                        A interdisciplinaridade como experiência metodológica na aprendizagem das habilidades motoras básicas — Ginástica Básica
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Maireylis Rabelo Valdivia, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Orlando Alberto, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Ética Académica</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Fraude académica e suas implicações na vida prática e profissional dos estudantes
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Lázaro Nganda Sapalo, Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Orlando Alberto, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Desempenho Docente</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Avaliação do impacto do processo de superação no desempenho dos Professores do Instituto Superior Politécnico Maravilha
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Domingos Caginga Quinzeca, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Orlando Alberto, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">História & Integração</span>
                                    </header>
                                    <h4 class="talk-title">
                                        O ensino da história como fundamento da integração africana: desafios para a construção de uma unidade continental no século XXI
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Solange Judite Gomes Amado Jorge</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> Orlando Alberto, MSc.</li>
                                    </ul>
                                </article>
                            </div>
                        </div>
                    </div>

                    {{-- PAINEL IV --}}
                    <div class="battery-block">
                        <div class="battery-head">
                            <div>
                                <span class="hbui-badge hbui-badge-default">Painel IV</span>
                                <h3 class="battery-title">Educação, Saúde e Desenvolvimento Social</h3>
                            </div>
                            <span class="battery-count">Sala 34</span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Desporto</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Impacto das actividades desportivas recreativas no desenvolvimento dos jovens nas comunidades de Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectores:</strong> Lorena Riveri Jimenes, MSc. · Rafael Torres Velazquez, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valaço, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Escrita Académica</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Escrita académica como instrumento de produção e valorização do conhecimento
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Adelino Tchendohamba Tchimbingo, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valaço, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">12h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Inclusão & Equidade</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Desafios da inclusão e equidade no ensino superior angolano: estudo interactivo com os estudantes do 2.º ano na especialidade de Biologia — ISPM Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Manuel Kandingolo Samuti, Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valaço, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Recursos Didácticos</span>
                                    </header>
                                    <h4 class="talk-title">
                                        Influência dos recursos didácticos visuais na motivação para o estudo de histologia — Instituto Técnico Privado de Saúde Bueia e Filhos, Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Eduardo André Hólwa, Estudante</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valaço, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h20</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Psicopedagogia</span>
                                    </header>
                                    <h4 class="talk-title">
                                        A constatação de histórias e o uso da inteligência artificial como estratégias psicopedagógicas no apoio emocional de crianças e adolescentes sinistrados das enchentes do rio Cavaco em Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> Luciano Kambili Sacutala, Lic.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valaço, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">13h40</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">História & Sociedade</span>
                                    </header>
                                    <h4 class="talk-title">
                                        As comemorações do 4 de Fevereiro e a trajectória da estrutura económica e social angolana: abordagem sociológica e transdisciplinar no processo de emancipação e desenvolvimento nacional
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectores:</strong> José Pedro da Cruz, PhD · José Muenho, PhD</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valaço, MSc.</li>
                                    </ul>
                                </article>
                            </div>

                            <div class="col-md-6 col-xl-4">
                                <article class="talk-card">
                                    <header>
                                        <span class="hbui-badge hbui-badge-default">14h00</span>
                                        <span class="hbui-badge hbui-badge-outline-soft">Ambiente</span>
                                    </header>
                                    <h4 class="talk-title">
                                        A análise da qualidade da água distribuída pela Empresa Pública da Água e Saneamento de Benguela
                                    </h4>
                                    <ul class="talk-meta">
                                        <li><i class="bi bi-mic-fill"></i> <strong>Prelectora:</strong> Iracelma Simão, MSc.</li>
                                        <li><i class="bi bi-person-fill"></i> <strong>Moderadora:</strong> Brigite Valaço, MSc.</li>
                                    </ul>
                                </article>
                            </div>
                        </div>
                    </div>

                    <h3 class="prog-block-title">
                        <i class="bi bi-flag-fill"></i> Sessão de Encerramento
                        <small>15h00 · Sala de Conferência</small>
                    </h3>

                    <div class="timeline">
                        <div class="timeline-item highlight">
                            <div class="timeline-time">15h00</div>
                            <div class="timeline-card">
                                <span class="hbui-badge hbui-badge-secondary">Encerramento</span>
                                <h4>Sessão de encerramento da XI Jornada</h4>
                                <p><i class="bi bi-award"></i> Momento cultural · Discurso de encerramento · Entrega de certificados de participação</p>
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
