<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'XI Jornada Científico-Metodológica Geral — ISPM')</title>
    <meta name="description" content="@yield('description', 'XI Jornada Científico-Metodológica Geral do Instituto Superior Politécnico Maravilha — 11 e 12 de Junho de 2026.')" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @if (!empty($branding['simbolo']))
        <link rel="icon" type="image/png" href="{{ $branding['simbolo'] }}" />
    @endif
    @stack('head')
</head>
<body class="@yield('body_class', 'landing-body')">

    <nav id="mainNav" class="navbar navbar-expand-lg sticky-top main-nav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                @if (!empty($branding['logo']))
                    <img src="{{ $branding['logo'] }}" alt="XI Jornada · ISPM" class="brand-logo" />
                @else
                    <span class="brand-mark">XI</span>
                    <span class="brand-text">
                        Jornada Científico-Metodológica
                        <small>ISPM · 2026</small>
                    </span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#evento">Evento</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#requisitos">Requisitos</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#cronograma">Cronograma</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#programa">Programa</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('submissao.create') }}">Submeter Trabalho</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('edicoes.index') }}">Edições</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}#contactos">Contactos</a></li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-cta" href="{{ route('inscricao.create') }}">
                            <i class="bi bi-pencil-square"></i> Inscrever
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')

    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="footer-brand">
                        @if (!empty($branding['logo']))
                            <img src="{{ $branding['logo'] }}" alt="XI Jornada · ISPM" class="footer-logo" />
                        @else
                            <span class="brand-mark">XI</span>
                            <div>
                                <strong>Jornada Científico-Metodológica</strong>
                                <small>ISPM · 2026</small>
                            </div>
                        @endif
                    </div>
                    <p class="mt-3 footer-text">
                        Instituto Superior Politécnico Maravilha — ISPM. Investigação,
                        ensino e desenvolvimento ao serviço de Angola.
                    </p>
                    <div class="footer-badges">
                        <span class="hbui-badge hbui-badge-outline">11–12 Jun · 2026</span>
                        <span class="hbui-badge hbui-badge-outline">Lema 2026</span>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <h6 class="footer-title">Navegação</h6>
                    <ul class="footer-list">
                        <li><a href="{{ route('home') }}">Evento</a></li>
                        <li><a href="{{ route('home') }}#requisitos">Requisitos</a></li>
                        <li><a href="{{ route('home') }}#cronograma">Cronograma</a></li>
                        <li><a href="{{ route('home') }}#programa">Programa</a></li>
                        <li><a href="{{ route('inscricao.create') }}">Inscrição</a></li>
                        <li><a href="{{ route('submissao.create') }}">Submeter Trabalho</a></li>
                        <li><a href="{{ route('programa') }}">Arquivo III edição</a></li>
                    </ul>
                </div>

                <div class="col-md-6 col-lg-4">
                    <h6 class="footer-title">Contactos</h6>
                    <ul class="footer-list">
                        <li><i class="bi bi-geo-alt"></i> Av. Aires de Almeida Santos</li>
                        <li><i class="bi bi-telephone"></i> 922 606 147 · 957 360 076 · 922 140 990</li>
                        <li><i class="bi bi-envelope"></i> vp.cientifica@ispmaravilha.com</li>
                        <li><i class="bi bi-globe"></i> www.ispmaravilha.com</li>
                    </ul>
                </div>
            </div>

            <hr class="footer-sep" />

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <small>© {{ date('Y') }} ISPM — XI Jornada Científico-Metodológica Geral. Todos os direitos reservados.</small>
                <small><a href="{{ route('admin.login') }}" class="text-muted"><i class="bi bi-shield-lock"></i> Área restrita</a></small>
            </div>
        </div>
    </footer>

    <button id="backToTop" class="back-to-top" aria-label="Voltar ao topo">
        <i class="bi bi-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
