@extends('layouts.app')

@section('title', $edicao->nome . ' — Arquivo')

@section('content')
<section class="landing-hero" style="background: linear-gradient(135deg, {{ $edicao->cor_primaria }}11, {{ $edicao->cor_secundaria }}11);">
    <div class="container landing-hero-inner">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="hero-badges mb-3">
                    @if ($edicao->status === 'actual')
                        <span class="hbui-badge hbui-badge-warning">Edição actual</span>
                    @elseif ($edicao->status === 'futura')
                        <span class="hbui-badge hbui-badge-default">Em preparação</span>
                    @else
                        <span class="hbui-badge hbui-badge-secondary">
                            <i class="bi bi-archive"></i> Arquivo
                        </span>
                    @endif
                    <span class="hbui-badge hbui-badge-outline">
                        <i class="bi bi-calendar-event"></i> {{ $edicao->data_extenso }}
                    </span>
                </div>

                <h1 class="hero-title">
                    <span class="text-accent">{{ $edicao->numero_romano }}</span>
                    {{ $edicao->nome }}
                </h1>

                @if ($edicao->lema)
                    <div class="lema-card">
                        <span class="lema-label"><i class="bi bi-quote"></i> Lema</span>
                        <p><strong>"{{ $edicao->lema }}"</strong></p>
                    </div>
                @endif

                @if ($edicao->descricao)
                    <p class="hero-lead">{{ $edicao->descricao }}</p>
                @endif

                @if ($edicao->local)
                    <p><i class="bi bi-geo-alt"></i> <strong>{{ $edicao->local }}</strong></p>
                @endif
            </div>

            <div class="col-lg-5">
                <div class="event-card">
                    <div class="event-status">
                        <div class="event-status-icon"><i class="bi bi-mortarboard-fill"></i></div>
                        <div>
                            <h3>{{ $edicao->numero_romano }} · {{ $edicao->nome_curto }}</h3>
                            <p>{{ $edicao->data_extenso }}</p>
                        </div>
                    </div>
                    <hr>
                    <ul class="ended-list">
                        <li><span class="ended-key">Tipo</span><span class="ended-val">{{ \App\Models\Edicao::TIPOS[$edicao->tipo] ?? $edicao->tipo }}</span></li>
                        @if ($edicao->presidente_nome)
                            <li><span class="ended-key">Presidente</span><span class="ended-val">{{ $edicao->presidente_nome }}</span></li>
                        @endif
                        @if ($miniCursos->isNotEmpty())
                            <li><span class="ended-key">Mini-cursos</span><span class="ended-val">{{ $miniCursos->count() }}</span></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($miniCursos->isNotEmpty())
    <section class="section section-alt">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Programa</span>
                <h2 class="section-title">Mini-<span class="text-accent">cursos</span></h2>
            </div>

            <div class="row g-4">
                @foreach ($miniCursos as $mc)
                    <div class="col-md-6 col-lg-4">
                        <article class="talk-card h-100">
                            <header>
                                <span class="hbui-badge hbui-badge-default">{{ $mc->hora }}</span>
                                <span class="hbui-badge hbui-badge-room">{{ $mc->local }}</span>
                                <span class="hbui-badge hbui-badge-outline-soft">{{ $mc->tema }}</span>
                            </header>
                            <h4 class="talk-title">{{ $mc->titulo }}</h4>
                            <ul class="talk-meta">
                                @if ($mc->prelector)
                                    <li><i class="bi bi-mic-fill"></i> <strong>Prelector:</strong> {{ $mc->prelector }}</li>
                                @endif
                                @if ($mc->moderador)
                                    <li><i class="bi bi-person-fill"></i> <strong>Moderador:</strong> {{ $mc->moderador }}</li>
                                @endif
                                <li><i class="bi bi-calendar2"></i> {{ $mc->dia_label }}</li>
                            </ul>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<section class="section">
    <div class="container text-center">
        <a href="{{ route('edicoes.index') }}" class="btn btn-ghost">
            <i class="bi bi-arrow-left"></i> Ver todas as edições
        </a>
        <a href="{{ route('home') }}" class="btn btn-cta">
            <i class="bi bi-house"></i> Página actual
        </a>
    </div>
</section>
@endsection
