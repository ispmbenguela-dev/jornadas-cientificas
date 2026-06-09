@extends('layouts.app')

@section('title', 'Avaliação encerrada — ISPM')

@section('content')
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 text-center">
                <div class="form-card py-5">
                    <div class="mb-4" style="font-size: 3.5rem; line-height:1; color: var(--color-primary, #0f4c81)">
                        <i class="bi bi-star-half"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Avaliação encerrada</h2>
                    <p class="text-muted mb-4">
                        O período de recolha de avaliações das Jornadas Científicas do ISPM
                        encontra-se encerrado. Obrigado pelo seu interesse.
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-cta">
                        <i class="bi bi-house"></i> Página inicial
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection