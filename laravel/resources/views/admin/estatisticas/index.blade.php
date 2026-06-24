@extends('layouts.admin')

@section('title', 'Estatística')
@section('page_title', 'Estatística')

@php
    $fmt = fn ($n) => number_format($n, 0, ',', '.');
    // Cores para as barras
    $cor = ['#0f4c81', '#198754', '#fd7e14', '#6f42c1', '#dc3545', '#0dcaf0', '#adb5bd'];
@endphp

@section('content')

    {{-- Cartões de topo --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100" style="background:#f0f4ff;border-left:4px solid #0f4c81">
                <i class="bi bi-people fs-2 text-primary opacity-75"></i>
                <div>
                    <div class="fw-bold fs-3 lh-1">{{ $fmt($total) }}</div>
                    <small class="text-muted">Inscrições totais</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100" style="background:#f0faf4;border-left:4px solid #198754">
                <i class="bi bi-check2-circle fs-2 text-success opacity-75"></i>
                <div>
                    <div class="fw-bold fs-3 lh-1 text-success">{{ $fmt($confirmadas) }}</div>
                    <small class="text-muted">Confirmadas</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100" style="background:#fff8f0;border-left:4px solid #fd7e14">
                <i class="bi bi-person-check fs-2 text-warning opacity-75"></i>
                <div>
                    <div class="fw-bold fs-3 lh-1" style="color:#fd7e14">{{ $fmt($presentes) }}</div>
                    <small class="text-muted">Presentes (check-in)</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100" style="background:#f6f0fc;border-left:4px solid #6f42c1">
                <i class="bi bi-cash-coin fs-2 opacity-75" style="color:#6f42c1"></i>
                <div>
                    <div class="fw-bold fs-5 lh-1" style="color:#6f42c1">{{ $fmt($receitaConfirmada) }} Kz</div>
                    <small class="text-muted">Receita confirmada</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100" style="background:#e8f4fd;border-left:4px solid #0d6efd">
                <i class="bi bi-gender-male fs-2 opacity-75" style="color:#0d6efd"></i>
                <div>
                    <div class="fw-bold fs-3 lh-1" style="color:#0d6efd">{{ $fmt($masculino) }}</div>
                    <small class="text-muted">Masculino (confirmados)</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 h-100" style="background:#fde8f4;border-left:4px solid #d63384">
                <i class="bi bi-gender-female fs-2 opacity-75" style="color:#d63384"></i>
                <div>
                    <div class="fw-bold fs-3 lh-1" style="color:#d63384">{{ $fmt($feminino) }}</div>
                    <small class="text-muted">Feminino (confirmados)</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Por sexo --}}
        <div class="col-lg-6">
            <div class="panel h-100">
                <h4><i class="bi bi-gender-ambiguous"></i> Por sexo</h4>
                @include('admin.estatisticas._barras', ['dados' => $porSexo, 'totalRef' => $total, 'cores' => $cor])
            </div>
        </div>

        {{-- Por categoria --}}
        <div class="col-lg-6">
            <div class="panel h-100">
                <h4><i class="bi bi-tags"></i> Por categoria</h4>
                @include('admin.estatisticas._barras', ['dados' => $porCategoria, 'totalRef' => $total, 'cores' => $cor])
            </div>
        </div>

        {{-- Por modalidade --}}
        <div class="col-lg-6">
            <div class="panel h-100">
                <h4><i class="bi bi-ui-checks-grid"></i> Por modalidade</h4>
                @include('admin.estatisticas._barras', ['dados' => $porModalidade, 'totalRef' => $total, 'cores' => $cor])
            </div>
        </div>

        {{-- Por estado --}}
        <div class="col-lg-6">
            <div class="panel h-100">
                <h4><i class="bi bi-flag"></i> Por estado</h4>
                @include('admin.estatisticas._barras', ['dados' => $porEstado, 'totalRef' => $total, 'cores' => $cor])
            </div>
        </div>

        {{-- Por mini-curso --}}
        <div class="col-lg-12">
            <div class="panel">
                <h4><i class="bi bi-easel"></i> Inscritos por mini-curso</h4>
                @if (count($porMiniCurso) === 0)
                    <p class="text-muted mb-0">Sem inscrições em mini-cursos.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr><th>Mini-curso</th><th class="text-nowrap">Horário</th><th>Local</th><th class="text-end">Inscritos</th></tr>
                            </thead>
                            <tbody>
                                @foreach ($porMiniCurso as $mc)
                                    <tr>
                                        <td>{{ $mc['titulo'] }}</td>
                                        <td class="text-nowrap text-muted">{{ $mc['hora'] ?? '—' }}</td>
                                        <td class="text-muted">{{ $mc['local'] ?? '—' }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $fmt($mc['total']) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Top instituições --}}
        <div class="col-lg-6">
            <div class="panel h-100">
                <h4><i class="bi bi-building"></i> Top instituições</h4>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <tbody>
                            @foreach ($topInstituicoes as $inst => $n)
                                <tr>
                                    <td>{{ $inst }}</td>
                                    <td class="text-end" style="width:70px"><span class="badge bg-secondary">{{ $fmt($n) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Resumo extra --}}
        <div class="col-lg-6">
            <div class="panel h-100">
                <h4><i class="bi bi-journal-text"></i> Submissões & outros</h4>
                <dl class="def-list mb-0">
                    <dt>Docentes ISPM</dt><dd>{{ $fmt($docentesIspm) }}</dd>
                    <dt>Receita total (calculada)</dt><dd>{{ $fmt($receitaTotal) }} Kz</dd>
                    <dt>Submissões totais</dt><dd>{{ $fmt($submissoes['total']) }}</dd>
                    <dt>— Admitidas</dt><dd>{{ $fmt($submissoes['admitidas']) }}</dd>
                    <dt>— Pendentes</dt><dd>{{ $fmt($submissoes['pendentes']) }}</dd>
                    <dt>— Rejeitadas</dt><dd>{{ $fmt($submissoes['rejeitadas']) }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection