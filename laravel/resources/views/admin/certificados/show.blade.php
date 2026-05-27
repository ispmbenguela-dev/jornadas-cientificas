@extends('layouts.admin')

@section('title', 'Certificado ' . $certificado->codigo)
@section('page_title', 'Certificado ' . $certificado->codigo)

@section('topbar_actions')
    <a href="{{ route('admin.certificados.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="panel">
                <h3>{{ $certificado->nome }}</h3>
                <p class="text-muted">{{ $certificado->tipo_label }}</p>

                <dl class="def-list">
                    <dt>Código</dt><dd><code>{{ $certificado->codigo }}</code></dd>
                    @if ($certificado->tema)
                        <dt>Tema</dt><dd>"{{ $certificado->tema }}"</dd>
                    @endif
                    @if ($certificado->papel_extra)
                        <dt>Papel extra</dt><dd>{{ $certificado->papel_extra }}</dd>
                    @endif
                    <dt>Data do evento</dt><dd>{{ $certificado->data_evento->translatedFormat('d \d\e F \d\e Y') }}</dd>
                    <dt>Emitido em</dt><dd>{{ $certificado->emitido_em?->format('d/m/Y H:i') }}</dd>
                    @if ($certificado->enviado_em)
                        <dt>Enviado em</dt><dd>{{ $certificado->enviado_em->format('d/m/Y H:i') }}</dd>
                    @endif
                    <dt>Estado</dt>
                    <dd>
                        @if ($certificado->estado === 'enviado')
                            <span class="badge bg-success">Enviado</span>
                        @elseif ($certificado->estado === 'descarregado')
                            <span class="badge bg-info">Descarregado</span>
                        @else
                            <span class="badge bg-warning text-dark">Emitido</span>
                        @endif
                    </dd>
                    @if ($certificado->email_destino)
                        <dt>E-mail destino</dt><dd>{{ $certificado->email_destino }}</dd>
                    @endif
                </dl>

                <div class="d-flex gap-2 flex-wrap mt-3">
                    <a href="{{ route('admin.certificados.download', $certificado) }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-download"></i> Descarregar
                    </a>
                    @if ($certificado->email_destino)
                        <form method="POST" action="{{ route('admin.certificados.enviar', $certificado) }}">
                            @csrf
                            <button class="btn btn-outline-success btn-sm">
                                <i class="bi bi-envelope"></i> {{ $certificado->enviado_em ? 'Reenviar e-mail' : 'Enviar por e-mail' }}
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.certificados.destroy', $certificado) }}"
                          onsubmit="return confirm('Remover certificado {{ $certificado->codigo }}?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </form>
                </div>

                <hr>
                <small class="text-muted">
                    Verificação pública:
                    <a href="{{ route('certificado.verify', $certificado->codigo) }}" target="_blank">
                        {{ route('certificado.verify', $certificado->codigo) }}
                    </a>
                </small>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="panel p-0" style="overflow:hidden;">
                <iframe src="{{ route('admin.certificados.preview', $certificado) }}"
                        style="width:100%; height:580px; border:0;"></iframe>
            </div>
        </div>
    </div>
@endsection