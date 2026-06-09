@extends('layouts.app')

@section('title', 'Inscrições encerradas')

@section('content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="success-card text-center">
                    <div class="success-icon" style="color: var(--muted, #888)">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <h2>Inscrições encerradas</h2>
                    <p class="text-muted">
                        O período de inscrições para
                        @if ($edicao)
                            a <strong>{{ $edicao->nome_curto ?? $edicao->nome }}</strong>
                        @else
                            esta edição
                        @endif
                        já terminou.
                    </p>

                    @if ($edicao?->inscricao_fim)
                        <p class="text-muted small">
                            Data limite: <strong>{{ $edicao->inscricao_fim->translatedFormat('d \d\e F \d\e Y') }}</strong>
                        </p>
                    @endif

                    <a href="{{ route('home') }}" class="btn btn-cta mt-2">
                        <i class="bi bi-house"></i> Voltar ao início
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
