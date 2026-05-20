@extends('layouts.app')

@section('title', 'Trabalho submetido — XI Jornada ISPM')

@section('content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="success-card">
                    <div class="success-icon"><i class="bi bi-cloud-check-fill"></i></div>
                    <h2>Trabalho submetido!</h2>
                    <p>
                        Olá <strong>{{ $submissao->autor_principal }}</strong>,
                        o trabalho “<strong>{{ $submissao->titulo }}</strong>”
                        foi submetido com sucesso (referência
                        <strong>#{{ str_pad($submissao->id, 4, '0', STR_PAD_LEFT) }}</strong>).
                        A Comissão Científica avaliará e divulgará a lista de
                        admitidos entre 18 e 24 de Maio de 2026.
                    </p>

                    <ul class="success-list">
                        <li><span>Ficheiro</span><strong>{{ $submissao->ficheiro_original }}</strong></li>
                        <li><span>Estado</span><strong>{{ $submissao->estado_label }}</strong></li>
                        <li><span>Submetido em</span><strong>{{ $submissao->created_at->format('d/m/Y H:i') }}</strong></li>
                    </ul>

                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <a href="{{ route('home') }}" class="btn btn-cta"><i class="bi bi-house"></i> Voltar ao início</a>
                        <a href="{{ route('inscricao.create') }}" class="btn btn-ghost"><i class="bi bi-pencil-square"></i> Inscrever-me</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
