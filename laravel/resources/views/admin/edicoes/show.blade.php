@extends('layouts.admin')

@section('title', $edicao->nome_curto ?? $edicao->nome)
@section('page_title', $edicao->numero_romano . ' · ' . $edicao->nome)

@section('topbar_actions')
    <a href="{{ route('admin.edicoes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <a href="{{ route('admin.edicoes.edit', $edicao) }}" class="btn btn-sm btn-cta">
        <i class="bi bi-pencil"></i> Editar
    </a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="panel">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-{{ $edicao->status === 'actual' ? 'success' : ($edicao->status === 'futura' ? 'info' : 'secondary') }}">
                        {{ $edicao->status_label }}
                    </span>
                    <small class="text-muted">/{{ $edicao->slug }}</small>
                </div>

                <h3>{{ $edicao->nome }}</h3>
                @if ($edicao->lema)
                    <p class="fst-italic text-muted">"{{ $edicao->lema }}"</p>
                @endif

                <dl class="def-list">
                    <dt>Data</dt><dd>{{ $edicao->data_extenso }}</dd>
                    <dt>Local</dt><dd>{{ $edicao->local ?: '—' }}</dd>
                    <dt>Presidente</dt><dd>{{ $edicao->presidente_nome }}</dd>
                    @if ($edicao->inscricao_inicio || $edicao->inscricao_fim)
                        <dt>Inscrições</dt>
                        <dd>{{ optional($edicao->inscricao_inicio)->format('d/m/Y') }} → {{ optional($edicao->inscricao_fim)->format('d/m/Y') }}</dd>
                    @endif
                    @if ($edicao->submissao_inicio || $edicao->submissao_fim)
                        <dt>Submissões</dt>
                        <dd>{{ optional($edicao->submissao_inicio)->format('d/m/Y') }} → {{ optional($edicao->submissao_fim)->format('d/m/Y') }}</dd>
                    @endif
                </dl>

                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="m-0">{{ $inscricoesTotais }}</h4>
                        <small class="text-muted">Inscrições</small>
                    </div>
                    <div class="col-6">
                        <h4 class="m-0">{{ $submissoesTotais }}</h4>
                        <small class="text-muted">Submissões</small>
                    </div>
                </div>
            </div>

            <div class="panel mt-3">
                <h4><i class="bi bi-cash-coin"></i> Taxas</h4>
                <table class="table table-sm">
                    <tr><th></th><th>Participação</th><th>Mini-Curso</th></tr>
                    @foreach (['docente' => 'Docente', 'estudante' => 'Estudante', 'publico' => 'Público'] as $k => $l)
                        <tr>
                            <td>{{ $l }}</td>
                            <td>{{ number_format($edicao->getTaxa($k, 'participacao'), 0, ',', '.') }} Kz</td>
                            <td>{{ number_format($edicao->getTaxa($k, 'mini_curso'), 0, ',', '.') }} Kz</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="m-0"><i class="bi bi-easel2"></i> Mini-cursos</h4>
                    <button class="btn btn-sm btn-cta" data-bs-toggle="collapse" data-bs-target="#novoMc">
                        <i class="bi bi-plus"></i> Adicionar
                    </button>
                </div>

                <div class="collapse mb-3" id="novoMc">
                    <form method="POST" action="{{ route('admin.edicoes.mini_curso.store', $edicao) }}" class="row g-2 border rounded p-3">
                        @csrf
                        <div class="col-md-4"><input type="text" name="chave" class="form-control form-control-sm" placeholder="chave (ex: dia1_14h_x)" required></div>
                        <div class="col-md-4"><input type="text" name="dia_label" class="form-control form-control-sm" placeholder="Dia (ex: 1.º Dia · 11 Jun)" required></div>
                        <div class="col-md-2"><input type="text" name="hora" class="form-control form-control-sm" placeholder="14h00" required></div>
                        <div class="col-md-2"><input type="text" name="local" class="form-control form-control-sm" placeholder="Sala X" required></div>
                        <div class="col-md-3"><input type="text" name="tema" class="form-control form-control-sm" placeholder="Tema/categoria" required></div>
                        <div class="col-md-9"><input type="text" name="titulo" class="form-control form-control-sm" placeholder="Título do mini-curso" required></div>
                        <div class="col-md-6"><input type="text" name="prelector" class="form-control form-control-sm" placeholder="Prelector"></div>
                        <div class="col-md-6"><input type="text" name="moderador" class="form-control form-control-sm" placeholder="Moderador"></div>
                        <div class="col-12"><button class="btn btn-sm btn-cta"><i class="bi bi-save"></i> Adicionar mini-curso</button></div>
                    </form>
                </div>

                @forelse ($miniCursos as $mc)
                    <div class="border rounded p-2 mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge bg-primary">{{ $mc->hora }}</span>
                                <span class="badge bg-info text-dark">{{ $mc->local }}</span>
                                <span class="badge bg-warning text-dark">{{ $mc->tema }}</span>
                                <small class="text-muted ms-2">{{ $mc->dia_label }}</small>
                                <small class="text-muted ms-1">· chave: <code>{{ $mc->chave }}</code></small>
                                @unless ($mc->activo)
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endunless
                            </div>
                            <form method="POST" action="{{ route('admin.edicoes.mini_curso.destroy', [$edicao, $mc]) }}"
                                  onsubmit="return confirm('Remover este mini-curso?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        <strong>{{ $mc->titulo }}</strong>
                        @if ($mc->prelector)<br><small><i class="bi bi-mic"></i> {{ $mc->prelector }}</small>@endif
                        @if ($mc->moderador)<br><small><i class="bi bi-person"></i> {{ $mc->moderador }}</small>@endif
                    </div>
                @empty
                    <p class="text-muted text-center py-3">Sem mini-cursos. Adicione o primeiro com o botão acima.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
