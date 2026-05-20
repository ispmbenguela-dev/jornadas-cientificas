@extends('layouts.app')

@section('title', 'Inscrição registada — XI Jornada ISPM')

@section('content')
<section class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="success-card">
                    <div class="success-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <h2>Inscrição registada com sucesso!</h2>
                    <p>
                        Olá <strong>{{ $inscricao->nome }}</strong>, a sua inscrição
                        número <strong>#{{ str_pad($inscricao->id, 4, '0', STR_PAD_LEFT) }}</strong>
                        foi registada. A Comissão Científica vai validar o pagamento
                        e confirmar por e-mail.
                    </p>

                    <ul class="success-list">
                        <li><span>Categoria</span><strong>{{ $inscricao->categoria_label }}</strong></li>
                        <li><span>Modalidade</span><strong>{{ $inscricao->modalidade_label }}</strong></li>
                        <li><span>Valor</span><strong>{{ $inscricao->valor_formatado }}</strong></li>
                        <li><span>Estado</span><strong>{{ ucfirst($inscricao->estado) }}</strong></li>
                    </ul>

                    @unless ($inscricao->comprovativo_path)
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle-fill"></i>
                            Ainda não enviou o comprovativo. Envie por e-mail para
                            <strong>vp.cientifica@ispmaravilha.com</strong> indicando
                            o número de inscrição <strong>#{{ $inscricao->id }}</strong>.
                        </div>
                    @endunless

                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <a href="{{ route('home') }}" class="btn btn-cta"><i class="bi bi-house"></i> Voltar ao início</a>
                        <a href="{{ route('submissao.create') }}" class="btn btn-ghost"><i class="bi bi-file-earmark-plus"></i> Submeter trabalho</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
