@extends('layouts.admin')

@section('title', 'QR Code — Avaliação')
@section('page_title', 'QR Code da Avaliação')

@section('topbar_actions')
    <a href="{{ route('admin.avaliacoes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <button onclick="window.print()" class="btn btn-sm btn-cta">
        <i class="bi bi-printer"></i> Imprimir
    </button>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="panel text-center" id="qr-print-area">
            <div class="mb-3">
                @if (!empty($branding['logo']))
                    <img src="{{ $branding['logo'] }}" alt="ISPM" style="max-height:60px; object-fit:contain" class="mb-3">
                @else
                    <div class="fw-bold fs-5 mb-2">Jornadas Científicas · ISPM</div>
                @endif
            </div>

            <h5 class="fw-bold mb-1">Formulário de Avaliação</h5>
            <p class="text-muted small mb-4">
                Digitalize o código QR para avaliar as Jornadas Científicas do ISPM.<br>
                A sua resposta é confidencial.
            </p>

            <div class="d-flex justify-content-center mb-4">
                <img src="{{ route('admin.avaliacoes.qrcode_img') }}"
                     alt="QR Code da Avaliação"
                     style="width:250px; height:250px; border:4px solid #f0f0f0; border-radius:12px">
            </div>

            <div class="mb-3">
                <code class="small text-muted" style="word-break:break-all">{{ $url }}</code>
            </div>

            <p class="text-muted" style="font-size:.8rem">
                <i class="bi bi-shield-check"></i>
                As respostas são confidenciais e destinam-se à melhoria das próximas edições.
            </p>
        </div>

        <div class="mt-3 d-flex gap-2 justify-content-center no-print">
            <a href="{{ route('admin.avaliacoes.qrcode_img') }}" download="qrcode-avaliacao.png"
               class="btn btn-outline-primary">
                <i class="bi bi-download"></i> Descarregar PNG
            </a>
            <a href="{{ $url }}" target="_blank" class="btn btn-outline-secondary">
                <i class="bi bi-box-arrow-up-right"></i> Abrir formulário
            </a>
        </div>
    </div>
</div>
@endsection

@push('head')
<style>
@media print {
    .admin-sidebar, .admin-topbar, .no-print { display: none !important; }
    .admin-main { margin: 0 !important; padding: 0 !important; }
    #qr-print-area { box-shadow: none !important; border: none !important; }
    body { background: #fff !important; }
}
</style>
@endpush