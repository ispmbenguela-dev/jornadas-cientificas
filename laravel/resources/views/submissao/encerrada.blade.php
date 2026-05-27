@extends('layouts.app')

@section('title', 'Submissões encerradas — XI Jornada ISPM')

@section('content')
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="form-card text-center">
                    <div style="width:80px;height:80px;border-radius:50%;background:#fef3c7;color:#92400e;display:flex;align-items:center;justify-content:center;font-size:40px;margin:0 auto 16px;">
                        <i class="bi bi-calendar-x-fill"></i>
                    </div>

                    <span class="section-eyebrow">Submissão de trabalhos</span>
                    <h2 class="section-title">
                        Período de submissão <span class="text-accent">encerrado</span>
                    </h2>

                    <p class="mt-3">
                        As submissões para a XI Jornada Científico-Metodológica fecharam em
                        <strong>{{ $prazoFinal->translatedFormat('d \d\e F \d\e Y') }}</strong>.
                    </p>

                    <p class="text-muted">
                        Se já enviou o seu trabalho, aguarde o parecer da Comissão Científica
                        que será comunicado por e-mail. Para questões pendentes contacte:
                        <a href="mailto:vp.cientifica@ispmaravilha.com">vp.cientifica@ispmaravilha.com</a>.
                    </p>

                    <hr>

                    <p>Pode continuar a inscrever-se para participar nas Jornadas.</p>

                    <div class="d-flex gap-2 flex-wrap justify-content-center mt-3">
                        <a href="{{ route('inscricao.create') }}" class="btn btn-cta">
                            <i class="bi bi-pencil-square"></i> Fazer inscrição
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-ghost">
                            <i class="bi bi-house"></i> Voltar ao início
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection