@extends('layouts.admin')

@section('title', 'Inscrições')
@section('page_title', 'Inscrições')

@section('content')
    <div class="panel">
        <form method="GET" class="filter-row">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nome, email ou telefone" />
            </div>
            <select name="estado" class="form-select">
                <option value="">Todos os estados</option>
                @foreach (['pendente', 'confirmada', 'rejeitada'] as $e)
                    <option value="{{ $e }}" @selected(request('estado') === $e)>{{ ucfirst($e) }}</option>
                @endforeach
            </select>
            <select name="categoria" class="form-select">
                <option value="">Todas as categorias</option>
                @foreach ($categorias as $k => $l)
                    <option value="{{ $k }}" @selected(request('categoria') === $k)>{{ $l }}</option>
                @endforeach
            </select>
            <button class="btn btn-cta btn-sm"><i class="bi bi-funnel"></i> Filtrar</button>
            <button type="submit" formaction="{{ route('admin.inscricoes.export') }}" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-spreadsheet"></i> Exportar CSV
            </button>
            <button type="submit" formaction="{{ route('admin.inscricoes.export_pdf') }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
            </button>
        </form>

        <div class="table-responsive mt-3">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Modalidade</th>
                        <th>Valor</th>
                        <th>Estado</th>
                        <th>Comprovativo</th>
                        <th>Criada</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($inscricoes as $i)
                    <tr>
                        <td>#{{ str_pad($i->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <strong>{{ $i->nome }}</strong>
                            <small class="d-block text-muted">{{ $i->email }} · {{ $i->telefone }}</small>
                        </td>
                        <td>{{ $i->categoria_label }}</td>
                        <td>
                            {{ $i->modalidade_label }}
                            @if ($i->mini_cursos_count > 0)
                                <small class="d-block text-muted">
                                    <strong>{{ $i->mini_cursos_count }} mini-curso(s):</strong>
                                    @foreach ($i->mini_cursos_list as $linha)
                                        <span class="d-block">• {{ $linha }}</span>
                                    @endforeach
                                </small>
                            @endif
                        </td>
                        <td>
                            {{ $i->valor_formatado }}
                            @if ($i->is_docente_ispm)
                                <small class="d-block text-success" title="Docente ISPM verificado SIGAM">
                                    <i class="bi bi-shield-check"></i> Doc. ISPM
                                </small>
                            @endif
                        </td>
                        <td>@include('admin._estado_badge', ['estado' => $i->estado])</td>
                        <td>
                            @if ($i->comprovativo_path)
                                <a href="{{ asset('storage/' . $i->comprovativo_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-paperclip"></i> Ver
                                </a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                            @if ($i->validacao_pagamento === 'ok')
                                <span class="badge bg-success" title="Valor declarado confere"><i class="bi bi-check2"></i></span>
                            @elseif ($i->validacao_pagamento === 'divergente')
                                <span class="badge bg-warning text-dark" title="Valor declarado diverge do total"><i class="bi bi-exclamation-triangle"></i></span>
                            @endif
                        </td>
                        <td><small>{{ $i->created_at->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('admin.inscricoes.show', $i) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-muted text-center py-4">Sem inscrições.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $inscricoes->links() }}
    </div>
@endsection
