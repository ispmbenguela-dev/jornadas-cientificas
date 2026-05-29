@extends('layouts.app')

@section('title', 'Edições anteriores — Jornadas Científicas ISPM')

@section('content')
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Arquivo</span>
            <h2 class="section-title">Edições <span class="text-accent">anteriores</span></h2>
            <p class="section-lead mx-auto" style="max-width: 720px">
                Histórico das Jornadas Científicas promovidas pelo Instituto Superior Politécnico Maravilha.
            </p>
        </div>

        <div class="row g-4">
            @forelse ($edicoes as $edicao)
                <div class="col-md-6 col-lg-4">
                    <div class="schedule-card" style="--accent: {{ $edicao->cor_secundaria }};">
                        <span class="schedule-step">{{ $edicao->numero_romano }}</span>
                        <h5>
                            <i class="bi bi-mortarboard"></i> {{ $edicao->nome_curto ?? $edicao->nome }}
                        </h5>
                        <p class="schedule-date">{{ $edicao->data_extenso }}</p>
                        @if ($edicao->lema)
                            <p class="schedule-desc fst-italic">"{{ \Illuminate\Support\Str::limit($edicao->lema, 100) }}"</p>
                        @endif
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            @if ($edicao->status === 'actual')
                                <span class="hbui-badge hbui-badge-success">Edição actual</span>
                            @elseif ($edicao->status === 'futura')
                                <span class="hbui-badge hbui-badge-default">Em breve</span>
                            @else
                                <span class="hbui-badge hbui-badge-outline">Arquivo</span>
                            @endif
                            <a href="{{ route('edicoes.show', $edicao->slug) }}" class="btn btn-sm btn-cta">
                                Ver detalhes <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Ainda não há edições arquivadas.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
