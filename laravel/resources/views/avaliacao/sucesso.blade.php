@extends('layouts.app')

@section('title', 'Avaliação submetida — ISPM')

@section('content')
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 text-center">
                <div class="form-card py-5">
                    <div class="mb-4">
                        <span style="font-size: 4rem; line-height:1">🎉</span>
                    </div>
                    <h2 class="fw-bold mb-3">Obrigado pela sua avaliação!</h2>
                    <p class="text-muted mb-4">
                        A sua resposta foi registada com sucesso. As suas opiniões são
                        fundamentais para a melhoria das próximas edições das Jornadas
                        Científicas do ISPM.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-cta">
                            <i class="bi bi-house"></i> Página inicial
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection