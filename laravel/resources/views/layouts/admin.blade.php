<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Admin') · XI Jornada ISPM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    @if (!empty($branding['simbolo']))
        <link rel="icon" type="image/png" href="{{ $branding['simbolo'] }}" />
    @endif
    @stack('head')
</head>
<body class="admin-body">

    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            @if (!empty($branding['logo']))
                <img src="{{ $branding['logo'] }}" alt="XI Jornada · ISPM" class="admin-logo" />
            @else
                <span class="brand-mark">XI</span>
                <div>
                    <strong>Painel Admin</strong>
                    <small>ISPM · 2026</small>
                </div>
            @endif
        </a>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.edicoes.index') }}" class="{{ request()->routeIs('admin.edicoes.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-event"></i> Edições
            </a>
            <a href="{{ route('admin.inscricoes.index') }}" class="{{ request()->routeIs('admin.inscricoes.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Inscrições
            </a>
            <a href="{{ route('admin.submissoes.index') }}" class="{{ request()->routeIs('admin.submissoes.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Submissões
            </a>
            <a href="{{ route('admin.mco.index') }}" class="{{ request()->routeIs('admin.mco.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Comissão Org.
            </a>
            <a href="{{ route('admin.certificados.index') }}" class="{{ request()->routeIs('admin.certificados.*') ? 'active' : '' }}">
                <i class="bi bi-patch-check"></i> Certificados
            </a>
            <a href="{{ route('admin.configuracoes.index') }}" class="{{ request()->routeIs('admin.configuracoes.*') ? 'active' : '' }}">
                <i class="bi bi-sliders"></i> Configurações
            </a>
            <a href="{{ route('home') }}" target="_blank">
                <i class="bi bi-box-arrow-up-right"></i> Ver site
            </a>
        </nav>

        <form method="POST" action="{{ route('admin.logout') }}" class="admin-logout">
            @csrf
            <div class="admin-user">
                <i class="bi bi-person-circle"></i>
                <div>
                    <strong>{{ auth()->user()?->name }}</strong>
                    <small>{{ auth()->user()?->email }}</small>
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-light w-100">
                <i class="bi bi-box-arrow-right"></i> Terminar sessão
            </button>
        </form>
    </aside>

    <main class="admin-main">
        <header class="admin-topbar">
            <h1 class="admin-page-title">@yield('page_title', 'Dashboard')</h1>
            @hasSection('topbar_actions')
                <div class="admin-topbar-actions">@yield('topbar_actions')</div>
            @endif
        </header>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
