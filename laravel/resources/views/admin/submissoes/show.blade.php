@extends('layouts.admin')

@section('title', 'Submissão #' . $submissao->id)
@section('page_title', 'Submissão #' . str_pad($submissao->id, 4, '0', STR_PAD_LEFT))

@section('topbar_actions')
    <a href="{{ route('admin.submissoes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel">
                <h3>{{ $submissao->titulo }}</h3>
                <p class="text-muted">por <strong>{{ $submissao->autor_principal }}</strong>
                    @if ($submissao->coautores) · {{ $submissao->coautores }} @endif
                </p>

                <dl class="def-list">
                    <dt>Instituição</dt><dd>{{ $submissao->instituicao }}</dd>
                    <dt>E-mail</dt><dd>{{ $submissao->email }}</dd>
                    <dt>Telefone</dt><dd>{{ $submissao->telefone ?: '—' }}</dd>
                    <dt>Área temática</dt><dd>{{ $submissao->area_tematica ?: '—' }}</dd>
                    <dt>Submetido</dt><dd>{{ $submissao->created_at->format('d/m/Y H:i') }}</dd>
                </dl>

                <h5 class="mt-4"><i class="bi bi-quote"></i> Resumo</h5>
                <p class="abstract">{{ $submissao->resumo }}</p>

                <a href="{{ asset('storage/' . $submissao->ficheiro_path) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="bi bi-file-earmark-arrow-down"></i> Abrir {{ $submissao->ficheiro_original }}
                </a>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel">
                <h4>Avaliação</h4>
                <form method="POST" action="{{ route('admin.submissoes.update', $submissao) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            @foreach (['pendente', 'admitida', 'rejeitada'] as $e)
                                <option value="{{ $e }}" @selected($submissao->estado === $e)>{{ ucfirst($e) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parecer</label>
                        <textarea name="parecer" rows="6" class="form-control">{{ $submissao->parecer }}</textarea>
                    </div>
                    <button class="btn btn-cta w-100"><i class="bi bi-save"></i> Guardar</button>
                </form>

                <hr class="my-4" />

                <form method="POST" action="{{ route('admin.submissoes.destroy', $submissao) }}"
                      onsubmit="return confirm('Eliminar esta submissão?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Eliminar submissão</button>
                </form>
            </div>
        </div>
    </div>
@endsection
