@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card kpi-primary">
                <div class="kpi-icon"><i class="bi bi-people"></i></div>
                <div>
                    <span>Inscrições totais</span>
                    <strong>{{ $stats['inscricoes_total'] }}</strong>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card kpi-warn">
                <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <span>Inscrições pendentes</span>
                    <strong>{{ $stats['inscricoes_pendentes'] }}</strong>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card kpi-success">
                <div class="kpi-icon"><i class="bi bi-check-circle"></i></div>
                <div>
                    <span>Confirmadas</span>
                    <strong>{{ $stats['inscricoes_confirmadas'] }}</strong>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card kpi-accent">
                <div class="kpi-icon"><i class="bi bi-cash-coin"></i></div>
                <div>
                    <span>Receita confirmada</span>
                    <strong>{{ number_format($stats['receita_kz'], 0, ',', '.') }} Kz</strong>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-4">
            <div class="kpi-card">
                <div class="kpi-icon"><i class="bi bi-journal-text"></i></div>
                <div>
                    <span>Submissões totais</span>
                    <strong>{{ $stats['submissoes_total'] }}</strong>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="kpi-card kpi-warn">
                <div class="kpi-icon"><i class="bi bi-clock"></i></div>
                <div>
                    <span>A avaliar</span>
                    <strong>{{ $stats['submissoes_pendentes'] }}</strong>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="kpi-card kpi-success">
                <div class="kpi-icon"><i class="bi bi-check2-square"></i></div>
                <div>
                    <span>Admitidas</span>
                    <strong>{{ $stats['submissoes_admitidas'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="panel">
                <header class="panel-head">
                    <h3><i class="bi bi-people"></i> Últimas inscrições</h3>
                    <a href="{{ route('admin.inscricoes.index') }}" class="small">Ver todas →</a>
                </header>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr><th>Nome</th><th>Categoria</th><th>Valor</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                        @forelse ($ultimasInscricoes as $i)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.inscricoes.show', $i) }}">{{ $i->nome }}</a>
                                    <small class="d-block text-muted">{{ $i->email }}</small>
                                </td>
                                <td>{{ $i->categoria_label }}</td>
                                <td>{{ number_format($i->valor_kz, 0, ',', '.') }} Kz</td>
                                <td>
                                    @include('admin._estado_badge', ['estado' => $i->estado])
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted text-center py-3">Sem inscrições ainda.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel">
                <header class="panel-head">
                    <h3><i class="bi bi-journal-text"></i> Últimas submissões</h3>
                    <a href="{{ route('admin.submissoes.index') }}" class="small">Ver todas →</a>
                </header>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr><th>Título</th><th>Autor</th><th>Estado</th></tr>
                        </thead>
                        <tbody>
                        @forelse ($ultimasSubmissoes as $s)
                            <tr>
                                <td><a href="{{ route('admin.submissoes.show', $s) }}">{{ \Illuminate\Support\Str::limit($s->titulo, 60) }}</a></td>
                                <td>{{ $s->autor_principal }}</td>
                                <td>@include('admin._estado_badge', ['estado' => $s->estado])</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-muted text-center py-3">Sem submissões ainda.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
