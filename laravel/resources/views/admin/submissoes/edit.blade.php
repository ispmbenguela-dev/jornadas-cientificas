@extends('layouts.admin')

@section('title', 'Editar Submissão #' . $submissao->id)
@section('page_title', 'Editar Submissão #' . str_pad($submissao->id, 4, '0', STR_PAD_LEFT))

@section('topbar_actions')
    <a href="{{ route('admin.submissoes.show', $submissao) }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.submissoes.update', $submissao) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="_full_edit" value="1">

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-file-earmark-text"></i> Trabalho</h4>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Título *</label>
                            <input type="text" name="titulo" value="{{ old('titulo', $submissao->titulo) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Área temática</label>
                            <select name="area_tematica" class="form-select">
                                <option value="">— Seleccione —</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area }}" @selected(old('area_tematica', $submissao->area_tematica) === $area)>{{ $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Resumo</label>
                            <textarea name="resumo" rows="6" class="form-control">{{ old('resumo', $submissao->resumo) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-people"></i> Autores</h4>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Autor principal *</label>
                            <input type="text" name="autor_principal" value="{{ old('autor_principal', $submissao->autor_principal) }}" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Coautores</label>
                            <input type="text" name="coautores" value="{{ old('coautores', $submissao->coautores) }}" class="form-control" placeholder="Separados por vírgula">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email" value="{{ old('email', $submissao->email) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" value="{{ old('telefone', $submissao->telefone) }}" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Instituição</label>
                            <input type="text" name="instituicao" value="{{ old('instituicao', $submissao->instituicao) }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-flag"></i> Avaliação</h4>
                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-select" required>
                            @foreach ($estados as $k => $label)
                                <option value="{{ $k }}" @selected(old('estado', $submissao->estado) === $k)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parecer</label>
                        <textarea name="parecer" rows="6" class="form-control">{{ old('parecer', $submissao->parecer) }}</textarea>
                    </div>
                </div>

                @if ($submissao->ficheiro_path)
                    <div class="panel mt-3">
                        <h4 class="mb-2"><i class="bi bi-paperclip"></i> Ficheiro</h4>
                        <a href="{{ asset('storage/' . $submissao->ficheiro_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-file-earmark-arrow-down"></i> {{ $submissao->ficheiro_original }}
                        </a>
                        <small class="text-muted d-block mt-1">Para substituir o ficheiro, elimine e recrie a submissão.</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-cta"><i class="bi bi-save"></i> Guardar alterações</button>
            <a href="{{ route('admin.submissoes.show', $submissao) }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection
