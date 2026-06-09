@extends('layouts.admin')

@section('title', 'Editar Inscrição #' . $inscricao->id)
@section('page_title', 'Editar Inscrição #' . str_pad($inscricao->id, 4, '0', STR_PAD_LEFT))

@section('topbar_actions')
    <a href="{{ route('admin.inscricoes.show', $inscricao) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.inscricoes.update', $inscricao) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="_full_edit" value="1">

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-person"></i> Dados pessoais</h4>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="nome" value="{{ old('nome', $inscricao->nome) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email" value="{{ old('email', $inscricao->email) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone *</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $inscricao->telefone) }}" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Instituição</label>
                            <input type="text" name="instituicao" value="{{ old('instituicao', $inscricao->instituicao) }}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input type="hidden" name="is_docente_ispm" value="0">
                                <input type="checkbox" name="is_docente_ispm" value="1" id="is_docente_ispm"
                                       class="form-check-input"
                                       @checked(old('is_docente_ispm', $inscricao->is_docente_ispm))>
                                <label class="form-check-label" for="is_docente_ispm">Docente ISPM verificado</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail institucional</label>
                            <input type="email" name="email_institucional" value="{{ old('email_institucional', $inscricao->email_institucional) }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-card-checklist"></i> Inscrição</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Categoria *</label>
                            <select name="categoria" class="form-select" required>
                                @foreach ($categorias as $k => $label)
                                    <option value="{{ $k }}" @selected(old('categoria', $inscricao->categoria) === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Modalidade *</label>
                            <select name="modalidade" class="form-select" required>
                                @foreach ($modalidades as $k => $label)
                                    <option value="{{ $k }}" @selected(old('modalidade', $inscricao->modalidade) === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (!empty($miniCursos))
                            <div class="col-12">
                                <label class="form-label">Mini-Cursos</label>
                                <div class="row g-2">
                                    @foreach ($miniCursos as $chave => $mc)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input type="checkbox" name="mini_cursos[]" value="{{ $chave }}"
                                                       id="mc_{{ $chave }}" class="form-check-input"
                                                       @checked(in_array($chave, old('mini_cursos', $inscricao->mini_cursos ?? [])))>
                                                <label class="form-check-label small" for="mc_{{ $chave }}">
                                                    {{ $mc['hora'] }} · {{ $mc['local'] }}<br>
                                                    <span class="text-muted">{{ $mc['titulo'] }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-cash-coin"></i> Pagamento</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Valor calculado (Kz)</label>
                            <input type="number" name="valor_kz" value="{{ old('valor_kz', $inscricao->valor_kz) }}" class="form-control" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Valor declarado (Kz)</label>
                            <input type="number" name="valor_pago_informado" value="{{ old('valor_pago_informado', $inscricao->valor_pago_informado) }}" class="form-control" min="0" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Referência</label>
                            <input type="text" name="referencia_pagamento" value="{{ old('referencia_pagamento', $inscricao->referencia_pagamento) }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Validação do pagamento</label>
                            <select name="validacao_pagamento" class="form-select">
                                @foreach ($validacoes as $k => $label)
                                    <option value="{{ $k }}" @selected(old('validacao_pagamento', $inscricao->validacao_pagamento) === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
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
                            @foreach (['pendente' => 'Pendente', 'confirmada' => 'Confirmada', 'rejeitada' => 'Rejeitada'] as $k => $label)
                                <option value="{{ $k }}" @selected(old('estado', $inscricao->estado) === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" rows="5" class="form-control">{{ old('observacoes', $inscricao->observacoes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-cta"><i class="bi bi-save"></i> Guardar alterações</button>
            <a href="{{ route('admin.inscricoes.show', $inscricao) }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
