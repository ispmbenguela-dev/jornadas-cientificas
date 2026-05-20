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
                                <span class="ended-val">04 — 30 Mai 2026</span>
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
                        <p class="schedule-date">04 — 30 de Maio · 2026</p>
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
                        <p class="schedule-date">18 — 24 de Maio · 2026</p>
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
                        </div>

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
                            <a href="mailto:ispm.direccao@hotmail.com">ispm.direccao@hotmail.com</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="bi bi-geo-alt"></i></div>
                        <h6>Localização</h6>
                        <p>
                            Av. Aires de Almeida Santos<br />
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
