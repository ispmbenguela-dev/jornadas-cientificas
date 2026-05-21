<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Admin — XI Jornada ISPM</title>
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
</head>
<body class="login-body">
    <div class="login-shell">
        <div class="login-card">
            <a href="{{ route('home') }}" class="login-brand">
                @if (!empty($branding['logo']))
                    <img src="{{ $branding['logo'] }}" alt="XI Jornada · ISPM" class="login-logo" />
                @else
                    <span class="brand-mark">XI</span>
                    <div>
                        <strong>Painel Admin</strong>
                        <small>XI Jornada · ISPM</small>
                    </div>
                @endif
            </a>

            <h1>Entrar</h1>
            <p class="text-muted">Acesso reservado à Comissão Científica. Credenciais autenticadas via <strong>SIGAM</strong>.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus />
                </div>
                <div class="mb-3">
                    <label class="form-label">Palavra-passe</label>
                    <input type="password" name="password" class="form-control" required />
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input" />
                    <label for="remember" class="form-check-label">Manter sessão iniciada</label>
                </div>
                <button type="submit" class="btn btn-cta w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Entrar
                </button>
            </form>

            <hr class="my-4" />
            <a href="{{ route('home') }}" class="text-muted small">
                <i class="bi bi-arrow-left"></i> Voltar ao site público
            </a>
        </div>
    </div>
</body>
</html>
