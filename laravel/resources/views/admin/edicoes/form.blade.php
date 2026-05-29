@extends('layouts.admin')

@php
    $editing = $edicao->exists ?? false;
@endphp

@section('title', $editing ? 'Editar edição' : 'Nova edição')
@section('page_title', $editing ? 'Editar: ' . $edicao->nome_curto : 'Nova edição')

@section('topbar_actions')
    <a href="{{ route('admin.edicoes.index') }}" class="btn btn-sm btn-outline-secondary">
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
          action="{{ $editing ? route('admin.edicoes.update', $edicao) : route('admin.edicoes.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-info-circle"></i> Identificação</h4>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Número romano *</label>
                            <input type="text" name="numero_romano" value="{{ old('numero_romano', $edicao->numero_romano) }}" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Número inteiro *</label>
                            <input type="number" name="numero_inteiro" value="{{ old('numero_inteiro', $edicao->numero_inteiro) }}" class="form-control" required min="1" max="999">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ano *</label>
                            <input type="number" name="ano" value="{{ old('ano', $edicao->ano) }}" class="form-control" required min="2000" max="2099">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo *</label>
                            <select name="tipo" class="form-select" required>
                                @foreach ($tipos as $k => $l)
                                    <option value="{{ $k }}" @selected(old('tipo', $edicao->tipo) === $k)>{{ $l }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Nome completo *</label>
                            <input type="text" name="nome" value="{{ old('nome', $edicao->nome) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nome curto</label>
                            <input type="text" name="nome_curto" value="{{ old('nome_curto', $edicao->nome_curto) }}" class="form-control" placeholder="ex.: XI Jornada">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Slug (URL) *</label>
                            <input type="text" name="slug" value="{{ old('slug', $edicao->slug) }}" class="form-control" required placeholder="xi-2026">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Lema</label>
                            <input type="text" name="lema" value="{{ old('lema', $edicao->lema) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="3">{{ old('descricao', $edicao->descricao) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-calendar2-event"></i> Datas</h4>

                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Início do evento *</label>
                            <input type="date" name="data_inicio" value="{{ old('data_inicio', optional($edicao->data_inicio)->format('Y-m-d')) }}" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Fim do evento *</label>
                            <input type="date" name="data_fim" value="{{ old('data_fim', optional($edicao->data_fim)->format('Y-m-d')) }}" class="form-control" required></div>

                        <div class="col-md-6"><label class="form-label">Inscrições — início</label>
                            <input type="date" name="inscricao_inicio" value="{{ old('inscricao_inicio', optional($edicao->inscricao_inicio)->format('Y-m-d')) }}" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Inscrições — fim</label>
                            <input type="date" name="inscricao_fim" value="{{ old('inscricao_fim', optional($edicao->inscricao_fim)->format('Y-m-d')) }}" class="form-control"></div>

                        <div class="col-md-6"><label class="form-label">Submissões — início</label>
                            <input type="date" name="submissao_inicio" value="{{ old('submissao_inicio', optional($edicao->submissao_inicio)->format('Y-m-d')) }}" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Submissões — fim</label>
                            <input type="date" name="submissao_fim" value="{{ old('submissao_fim', optional($edicao->submissao_fim)->format('Y-m-d')) }}" class="form-control"></div>

                        <div class="col-md-6"><label class="form-label">Trabalhos admitidos — início</label>
                            <input type="date" name="trabalhos_admitidos_inicio" value="{{ old('trabalhos_admitidos_inicio', optional($edicao->trabalhos_admitidos_inicio)->format('Y-m-d')) }}" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Trabalhos admitidos — fim</label>
                            <input type="date" name="trabalhos_admitidos_fim" value="{{ old('trabalhos_admitidos_fim', optional($edicao->trabalhos_admitidos_fim)->format('Y-m-d')) }}" class="form-control"></div>
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-cash-coin"></i> Taxas (Kz)</h4>
                    @php
                        $taxas = old('taxa_docente_part') !== null ? null : ($edicao->taxas ?? \App\Models\Edicao::TAXAS_DEFAULT);
                    @endphp
                    <div class="row g-2">
                        @foreach (['docente', 'estudante', 'publico'] as $cat)
                            <div class="col-12"><strong class="text-uppercase small">{{ ucfirst($cat) }}</strong></div>
                            <div class="col-md-4 col-6">
                                <label class="form-label small">Participação</label>
                                <input type="number" name="taxa_{{ $cat }}_part"
                                       value="{{ old('taxa_'.$cat.'_part', $taxas[$cat]['participacao'] ?? '') }}"
                                       class="form-control" min="0">
                            </div>
                            <div class="col-md-4 col-6">
                                <label class="form-label small">Mini-Curso (cada)</label>
                                <input type="number" name="taxa_{{ $cat }}_mini"
                                       value="{{ old('taxa_'.$cat.'_mini', $taxas[$cat]['mini_curso'] ?? '') }}"
                                       class="form-control" min="0">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-flag"></i> Estado e visibilidade</h4>

                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="status" class="form-select" required>
                            @foreach ($status as $k => $l)
                                <option value="{{ $k }}" @selected(old('status', $edicao->status) === $k)>{{ $l }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Só uma edição pode ser "actual" — as outras passam automaticamente a "passada".</small>
                    </div>

                    <div class="form-check mb-3">
                        <input type="hidden" name="mostrar_no_arquivo" value="0">
                        <input type="checkbox" name="mostrar_no_arquivo" value="1" id="mostrar_no_arquivo"
                               class="form-check-input"
                               @checked(old('mostrar_no_arquivo', $edicao->mostrar_no_arquivo ?? true))>
                        <label class="form-check-label" for="mostrar_no_arquivo">Mostrar no arquivo público</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Local</label>
                        <input type="text" name="local" value="{{ old('local', $edicao->local) }}" class="form-control">
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-person-badge"></i> Presidência</h4>
                    <div class="mb-2">
                        <label class="form-label small">Título</label>
                        <input type="text" name="presidente_titulo" value="{{ old('presidente_titulo', $edicao->presidente_titulo) }}" class="form-control">
                    </div>
                    <div>
                        <label class="form-label small">Nome</label>
                        <input type="text" name="presidente_nome" value="{{ old('presidente_nome', $edicao->presidente_nome) }}" class="form-control">
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-palette"></i> Marca da edição</h4>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small">Cor primária</label>
                            <input type="color" name="cor_primaria" value="{{ old('cor_primaria', $edicao->cor_primaria ?? '#0f4c81') }}" class="form-control form-control-color" style="width:100%">
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Cor secundária</label>
                            <input type="color" name="cor_secundaria" value="{{ old('cor_secundaria', $edicao->cor_secundaria ?? '#f37021') }}" class="form-control form-control-color" style="width:100%">
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label small">Banner</label>
                            <input type="file" name="banner" accept=".png,.jpg,.jpeg,.webp" class="form-control form-control-sm">
                            @if ($editing && $edicao->banner_url)
                                <img src="{{ $edicao->banner_url }}" alt="" class="mt-2" style="max-width:100%; border-radius:8px;">
                                <div class="form-check mt-1">
                                    <input type="checkbox" name="remover_banner" value="1" id="remover_banner" class="form-check-input">
                                    <label class="form-check-label small text-danger" for="remover_banner">Remover banner</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-cta"><i class="bi bi-save"></i> {{ $editing ? 'Guardar alterações' : 'Criar edição' }}</button>
            <a href="{{ route('admin.edicoes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
