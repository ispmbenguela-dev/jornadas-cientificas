@extends('layouts.admin')

@php $editing = $membro->exists; @endphp

@section('title', $editing ? 'Editar membro MCO' : 'Novo membro MCO')
@section('page_title', $editing ? 'Editar: ' . $membro->nome : 'Adicionar membro da Comissão Organizadora')

@section('topbar_actions')
    <a href="{{ route('admin.mco.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST"
          action="{{ $editing ? route('admin.mco.update', $membro) : route('admin.mco.store') }}">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-person"></i> Dados do membro</h4>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nome completo *</label>
                            <input type="text" name="nome"
                                   value="{{ old('nome', $membro->nome) }}"
                                   class="form-control" required maxlength="160">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $membro->email) }}"
                                   class="form-control" required maxlength="160">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone *</label>
                            <input type="text" name="telefone"
                                   value="{{ old('telefone', $membro->telefone) }}"
                                   class="form-control" required maxlength="40">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Instituição</label>
                            <input type="text" name="instituicao"
                                   value="{{ old('instituicao', $membro->instituicao) }}"
                                   class="form-control" maxlength="160"
                                   placeholder="ex.: ISPM">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-flag"></i> Estado</h4>
                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-select" required>
                            <option value="confirmada" @selected(old('estado', $membro->estado ?? 'confirmada') === 'confirmada')>Confirmada</option>
                            <option value="pendente"   @selected(old('estado', $membro->estado) === 'pendente')>Pendente</option>
                            <option value="rejeitada"  @selected(old('estado', $membro->estado) === 'rejeitada')>Rejeitada</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" rows="4" class="form-control">{{ old('observacoes', $membro->observacoes) }}</textarea>
                    </div>

                    @if ($edicao)
                        <p class="text-muted small mb-0">
                            <i class="bi bi-info-circle"></i>
                            Será associado à edição <strong>{{ $edicao->nome_curto }}</strong>.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-cta">
                <i class="bi bi-save"></i> {{ $editing ? 'Guardar alterações' : 'Adicionar membro' }}
            </button>
            <a href="{{ route('admin.mco.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
