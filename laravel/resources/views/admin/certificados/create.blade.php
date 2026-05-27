@extends('layouts.admin')

@section('title', 'Emitir certificado')
@section('page_title', 'Emitir certificado manualmente')

@section('topbar_actions')
    <a href="{{ route('admin.certificados.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="panel">
                <p class="text-muted small">
                    Use este formulário para emitir certificados a prelectores, moderadores ou organizadores
                    que não tenham inscrição associada. Para participantes, prefira o botão "Gerar para
                    inscrições confirmadas".
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.certificados.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Tipo *</label>
                        <select name="tipo" class="form-select" required>
                            <option value="">— Seleccione —</option>
                            @foreach ($tipos as $k => $l)
                                <option value="{{ $k }}" @selected(old('tipo') === $k)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Data do evento *</label>
                        <input type="date" name="data_evento" value="{{ old('data_evento', '2026-06-12') }}" class="form-control" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Nome completo *</label>
                        <input type="text" name="nome" value="{{ old('nome') }}" class="form-control" required maxlength="200">
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Tema / Painel
                            <small class="text-muted">(obrigatório para prelectores e moderadores)</small>
                        </label>
                        <input type="text" name="tema" value="{{ old('tema') }}" class="form-control" maxlength="300" placeholder="ex.: Aplicação da Inteligência Artificial no Ensino e aprendizagem">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Papel extra <small class="text-muted">(opcional)</small></label>
                        <input type="text" name="papel_extra" value="{{ old('papel_extra') }}" class="form-control" maxlength="120" placeholder="ex.: Coordenador Científico, Secretária da Comissão">
                    </div>

                    <div class="col-12 d-flex gap-2 mt-3">
                        <button class="btn btn-cta">
                            <i class="bi bi-patch-check"></i> Emitir certificado
                        </button>
                        <a href="{{ route('admin.certificados.index') }}" class="btn btn-ghost">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection