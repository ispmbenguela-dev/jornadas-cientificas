@extends('layouts.app')

@section('title', 'Verificação de certificado — XI Jornada ISPM')

@section('content')
<section class="section section-alt">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <span class="section-eyebrow">Validação online</span>
                    <h2 class="section-title">Verificar <span class="text-accent">certificado</span></h2>
                </div>

                @if ($certificado)
                    <div class="form-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:56px;height:56px;border-radius:50%;background:#d1fae5;color:#065f46;display:flex;align-items:center;justify-content:center;font-size:28px;">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">Certificado válido</h3>
                                <small class="text-muted">Emitido pelo Instituto Superior Politécnico Maravilha</small>
                            </div>
                        </div>

                        <hr>

                        <dl class="row">
                            <dt class="col-sm-4 text-muted">Código</dt>
                            <dd class="col-sm-8"><code>{{ $certificado->codigo }}</code></dd>

                            <dt class="col-sm-4 text-muted">Nome</dt>
                            <dd class="col-sm-8"><strong>{{ $certificado->nome }}</strong></dd>

                            <dt class="col-sm-4 text-muted">Tipo</dt>
                            <dd class="col-sm-8">{{ $certificado->tipo_label }}</dd>

                            @if ($certificado->tema)
                                <dt class="col-sm-4 text-muted">Tema</dt>
                                <dd class="col-sm-8">"{{ $certificado->tema }}"</dd>
                            @endif

                            <dt class="col-sm-4 text-muted">Evento</dt>
                            <dd class="col-sm-8">XI Jornada Científico-Metodológica · ISPM Benguela</dd>

                            <dt class="col-sm-4 text-muted">Data do evento</dt>
                            <dd class="col-sm-8">{{ $certificado->data_evento->translatedFormat('d \d\e F \d\e Y') }}</dd>

                            <dt class="col-sm-4 text-muted">Emitido em</dt>
                            <dd class="col-sm-8">{{ $certificado->emitido_em?->format('d/m/Y H:i') }}</dd>
                        </dl>

                        @if ($certificado->pdf_path)
                            <a href="{{ route('certificado.download', $certificado->codigo) }}" class="btn btn-cta">
                                <i class="bi bi-download"></i> Descarregar PDF
                            </a>
                        @endif
                    </div>
                @else
                    <div class="form-card text-center">
                        <div style="width:80px;height:80px;border-radius:50%;background:#fee2e2;color:#991b1b;display:flex;align-items:center;justify-content:center;font-size:40px;margin:0 auto 16px;">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <h3>Certificado não encontrado</h3>
                        <p class="text-muted">
                            O código <code>{{ $codigo }}</code> não corresponde a nenhum certificado
                            emitido pelo ISPM. Verifique se foi escrito correctamente.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-ghost">
                            <i class="bi bi-arrow-left"></i> Voltar ao início
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection