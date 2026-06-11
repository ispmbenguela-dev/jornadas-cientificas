@extends('layouts.admin')

@section('title', 'Verificação · Portaria')
@section('page_title', 'Verificação de Inscrições')

@section('content')
@push('head')
<style>
    /* --- layout mobile-first --- */
    .verif-search-wrap {
        position: sticky; top: 0; z-index: 100;
        background: #fff;
        padding: 1rem 0 .75rem;
        margin-bottom: 1.25rem;
    }
    .verif-input {
        font-size: 1.2rem;
        height: 3.2rem;
        border-radius: .75rem;
        border: 2px solid #0f4c81;
        padding-left: 3rem;
    }
    .verif-input:focus { box-shadow: 0 0 0 3px rgba(15,76,129,.2); border-color:#0f4c81; }
    .verif-search-icon {
        position:absolute; left:1rem; top:50%; transform:translateY(-50%);
        font-size:1.3rem; color:#0f4c81; pointer-events:none;
    }
    .verif-btn {
        height:3.2rem; border-radius:.75rem; font-size:1rem; font-weight:600;
    }

    /* --- cartão de resultado --- */
    .verif-card {
        border-radius: 1rem;
        border: 2px solid #dee2e6;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,.07);
    }
    .verif-card-header {
        padding: .9rem 1.1rem;
        display: flex; align-items: center; gap: .75rem;
    }
    .verif-card-header.confirmada { background: #d1fae5; border-bottom: 2px solid #198754; }
    .verif-card-header.pendente   { background: #fff3cd; border-bottom: 2px solid #ffc107; }
    .verif-card-header.rejeitada  { background: #fee2e2; border-bottom: 2px solid #dc3545; }

    .verif-estado-icon { font-size: 2rem; flex-shrink: 0; }
    .verif-nome  { font-size: 1.15rem; font-weight: 700; line-height: 1.2; }
    .verif-email { font-size: .82rem; color: #6c757d; }

    .verif-card-body { padding: 1rem 1.1rem; }

    .verif-pill-list { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .3rem; }
    .verif-pill {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .3rem .75rem; border-radius: 2rem;
        font-size: .8rem; font-weight: 600;
    }
    .verif-pill-participacao { background: #e0f0ff; color: #0f4c81; }
    .verif-pill-mini         { background: #d1fae5; color: #065f46; }

    .verif-row { display: flex; justify-content: space-between; padding: .35rem 0; border-bottom: 1px solid #f0f0f0; font-size: .9rem; }
    .verif-row:last-child { border-bottom: none; }
    .verif-row-key   { color: #6c757d; }
    .verif-row-val   { font-weight: 600; text-align: right; }

    .verif-mc-item {
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: .5rem; padding: .45rem .75rem;
        font-size: .83rem; margin-top: .4rem;
    }
    .verif-mc-item strong { display: block; font-size: .88rem; }

    .verif-empty {
        text-align: center; padding: 3rem 1rem;
        color: #6c757d;
    }
    .verif-empty-icon { font-size: 4rem; display: block; margin-bottom: .75rem; opacity: .4; }

    /* badge de estado grande */
    .verif-estado-badge {
        display: inline-block; padding: .3rem .9rem;
        border-radius: 2rem; font-size: .78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
    }
    .verif-estado-badge.confirmada { background:#198754; color:#fff; }
    .verif-estado-badge.pendente   { background:#ffc107; color:#000; }
    .verif-estado-badge.rejeitada  { background:#dc3545; color:#fff; }

    /* estado sem resultados */
    .verif-nenhum {
        background: #fee2e2; border: 2px solid #dc3545;
        border-radius: 1rem; padding: 1.5rem 1.25rem; text-align: center;
    }
    .verif-card-checkedin {
        border-color: #198754 !important;
        box-shadow: 0 0 0 3px rgba(25,135,84,.15) !important;
    }
</style>
@endpush

{{-- Barra de pesquisa sticky --}}
<div class="verif-search-wrap">
    <form method="GET" action="{{ route('admin.verificacao.index') }}">
        <div class="d-flex gap-2">
            <div class="position-relative flex-grow-1">
                <i class="bi bi-search verif-search-icon"></i>
                <input
                    type="search"
                    name="q"
                    value="{{ $q }}"
                    class="form-control verif-input"
                    placeholder="Nome, e-mail ou telefone…"
                    autofocus
                    autocomplete="off"
                />
            </div>
            <button class="btn btn-cta verif-btn px-4">
                <i class="bi bi-search"></i>
            </button>
            @if ($q)
            <a href="{{ route('admin.verificacao.index') }}" class="btn btn-outline-secondary verif-btn px-3">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>
</div>

{{-- Resultados --}}
@if ($q === '')
    <div class="verif-empty">
        <i class="bi bi-person-badge verif-empty-icon"></i>
        <p class="fw-semibold mb-1">Pesquise um participante</p>
        <small>Introduza o nome, e-mail ou telefone do participante para verificar a inscrição.</small>
    </div>

@elseif ($resultados->isEmpty())
    <div class="verif-nenhum">
        <i class="bi bi-x-circle-fill fs-1 text-danger d-block mb-2"></i>
        <p class="fw-bold fs-5 mb-1">Nenhuma inscrição encontrada</p>
        <small class="text-muted">Não foi encontrado nenhum registo para "<strong>{{ $q }}</strong>".</small>
    </div>

@else
    <p class="text-muted small mb-2">
        {{ $resultados->count() }} resultado(s) para "<strong>{{ $q }}</strong>"
    </p>

    @foreach ($resultados as $i)
    @php
        $estadoIcon = match($i->estado) {
            'confirmada' => '✅',
            'pendente'   => '⏳',
            'rejeitada'  => '❌',
            default      => '❓',
        };
        $miniCursos = is_array($i->mini_cursos) ? $i->mini_cursos : [];
    @endphp

    <div class="verif-card {{ $i->checked_in_at ? 'verif-card-checkedin' : '' }}">
        {{-- Cabeçalho com nome e estado --}}
        <div class="verif-card-header {{ $i->estado }}">
            <span class="verif-estado-icon">{{ $estadoIcon }}</span>
            <div class="flex-grow-1">
                <div class="verif-nome">{{ $i->nome }}</div>
                <div class="verif-email">{{ $i->email }} · {{ $i->telefone }}</div>
            </div>
            <span class="verif-estado-badge {{ $i->estado }}">{{ ucfirst($i->estado) }}</span>
        </div>

        {{-- Corpo --}}
        <div class="verif-card-body">

            {{-- Modalidade como pills --}}
            <div class="mb-3">
                @if ($i->modalidade === 'participacao' || $i->modalidade === 'mini_curso')
                    <div class="verif-pill-list">
                        @if ($i->modalidade === 'participacao')
                            <span class="verif-pill verif-pill-participacao">
                                <i class="bi bi-ticket-perforated"></i> Participação
                            </span>
                        @endif
                        @if (count($miniCursos) > 0)
                            <span class="verif-pill verif-pill-mini">
                                <i class="bi bi-journal-bookmark-fill"></i>
                                {{ count($miniCursos) }} Mini-curso(s)
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Lista de mini-cursos --}}
                @foreach($i->mini_cursos_list ?? [] as $mc)
                    <div class="verif-mc-item">
                        <i class="bi bi-check-circle-fill text-success"></i>
                        {{ $mc }}
                    </div>
                @endforeach
            </div>

            {{-- Dados complementares --}}
            <div>
                <div class="verif-row">
                    <span class="verif-row-key"><i class="bi bi-person-workspace"></i> Categoria</span>
                    <span class="verif-row-val">{{ $i->categoria_label }}</span>
                </div>
                <div class="verif-row">
                    <span class="verif-row-key"><i class="bi bi-building"></i> Instituição</span>
                    <span class="verif-row-val">{{ $i->instituicao ?: '—' }}</span>
                </div>
                <div class="verif-row">
                    <span class="verif-row-key"><i class="bi bi-cash"></i> Valor</span>
                    <span class="verif-row-val">
                        @if ($i->valor_kz == 0)
                            <span class="text-success">Gratuito</span>
                        @else
                            {{ number_format($i->valor_kz, 0, ',', '.') }} Kz
                        @endif
                    </span>
                </div>
                <div class="verif-row">
                    <span class="verif-row-key"><i class="bi bi-calendar3"></i> Inscrição</span>
                    <span class="verif-row-val">{{ $i->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if ($i->comprovativo_path)
                <div class="verif-row">
                    <span class="verif-row-key"><i class="bi bi-paperclip"></i> Comprovativo</span>
                    <span class="verif-row-val">
                        <a href="{{ asset('storage/' . $i->comprovativo_path) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </span>
                </div>
                @endif

                {{-- Check-in --}}
                <div class="verif-row" style="border-top:2px solid #e9ecef; margin-top:.5rem; padding-top:.6rem">
                    <span class="verif-row-key">
                        <i class="bi bi-door-open"></i> Entrada
                    </span>
                    <span class="verif-row-val">
                        @if ($i->checked_in_at)
                            <span class="text-success fw-bold small">
                                <i class="bi bi-check-circle-fill"></i>
                                {{ $i->checked_in_at->format('H:i') }}
                            </span>
                        @else
                            <span class="text-muted small">Não registada</span>
                        @endif
                    </span>
                </div>
            </div>

            {{-- Botão check-in --}}
            <form method="POST"
                  action="{{ route('admin.verificacao.checkin', [$i, 'q' => $q]) }}"
                  class="mt-3">
                @csrf
                @if ($i->checked_in_at)
                    <button type="submit"
                            class="btn w-100 btn-outline-secondary"
                            style="font-size:1rem; height:3rem; border-radius:.75rem">
                        <i class="bi bi-arrow-counterclockwise"></i> Anular entrada
                    </button>
                @else
                    <button type="submit"
                            class="btn w-100"
                            style="font-size:1.1rem; font-weight:700; height:3.2rem; border-radius:.75rem;
                                   background:#198754; color:#fff; border:none">
                        <i class="bi bi-door-open-fill"></i> Marcar entrada
                    </button>
                @endif
            </form>

        </div>
    </div>
    @endforeach
@endif

@endsection
