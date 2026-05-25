@extends('layouts.admin')

@section('title', 'Inscrição #' . $inscricao->id)
@section('page_title', 'Inscrição #' . str_pad($inscricao->id, 4, '0', STR_PAD_LEFT))

@section('topbar_actions')
    <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel">
                <h3>{{ $inscricao->nome }}</h3>
                <p class="text-muted mb-3">{{ $inscricao->email }} · {{ $inscricao->telefone }}</p>

                <dl class="def-list">
                    <dt>Instituição</dt><dd>{{ $inscricao->instituicao ?: '—' }}</dd>
                    <dt>Categoria</dt><dd>{{ $inscricao->categoria_label }}</dd>
                    @if ($inscricao->is_docente_ispm)
                        <dt>Docente ISPM</dt>
                        <dd>
                            <span class="badge bg-success"><i class="bi bi-shield-check"></i> Verificado SIGAM</span>
                            @if ($inscricao->email_institucional)
                                <small class="d-block text-muted mt-1">{{ $inscricao->email_institucional }}</small>
                            @endif
                            @if (!empty($inscricao->verificacao_sigam['user']['name']))
                                <small class="d-block text-muted">{{ $inscricao->verificacao_sigam['user']['name'] }}</small>
                            @endif
                        </dd>
                    @endif
                    <dt>Modalidade</dt><dd>{{ $inscricao->modalidade_label }}</dd>
                    @if ($inscricao->mini_cursos_count > 0)
                        <dt>Mini-Cursos ({{ $inscricao->mini_cursos_count }})</dt>
                        <dd>
                            <ul class="mb-0 ps-3">
                                @foreach ($inscricao->mini_cursos_list as $linha)
                                    <li>{{ $linha }}</li>
                                @endforeach
                            </ul>
                        </dd>
                    @endif
                    <dt>Valor calculado</dt><dd>{{ $inscricao->valor_formatado }}</dd>
                    @if ($inscricao->valor_pago_informado !== null)
                        <dt>Valor declarado</dt>
                        <dd>{{ number_format($inscricao->valor_pago_informado, 2, ',', '.') }} Kz</dd>
                        <dt>Referência</dt><dd>{{ $inscricao->referencia_pagamento ?: '—' }}</dd>
                        <dt>Validação</dt>
                        <dd>
                            @if ($inscricao->validacao_pagamento === 'ok')
                                <span class="badge bg-success"><i class="bi bi-check2-circle"></i> {{ $inscricao->validacao_pagamento_label }}</span>
                            @elseif ($inscricao->validacao_pagamento === 'divergente')
                                <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle"></i> {{ $inscricao->validacao_pagamento_label }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $inscricao->validacao_pagamento_label }}</span>
                            @endif
                        </dd>
                    @endif
                    <dt>Criada</dt><dd>{{ $inscricao->created_at->format('d/m/Y H:i') }}</dd>
                </dl>

                @if ($inscricao->comprovativo_path)
                    <a href="{{ asset('storage/' . $inscricao->comprovativo_path) }}" target="_blank" class="btn btn-outline-primary">
                        <i class="bi bi-paperclip"></i> Abrir comprovativo
                    </a>
                @else
                    <span class="badge bg-warning"><i class="bi bi-exclamation-circle"></i> Sem comprovativo enviado</span>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel">
                <h4>Actualizar estado</h4>
                <form method="POST" action="{{ route('admin.inscricoes.update', $inscricao) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            @foreach (['pendente', 'confirmada', 'rejeitada'] as $e)
                                <option value="{{ $e }}" @selected($inscricao->estado === $e)>{{ ucfirst($e) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" rows="4" class="form-control">{{ $inscricao->observacoes }}</textarea>
                    </div>
                    <button class="btn btn-cta w-100"><i class="bi bi-save"></i> Guardar</button>
                </form>

                <hr class="my-4" />

                <form method="POST" action="{{ route('admin.inscricoes.destroy', $inscricao) }}"
                      onsubmit="return confirm('Eliminar esta inscrição?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger w-100"><i class="bi bi-trash"></i> Eliminar inscrição</button>
                </form>
            </div>
        </div>
    </div>
@endsection
