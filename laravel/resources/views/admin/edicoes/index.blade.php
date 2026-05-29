@extends('layouts.admin')

@section('title', 'Edições da Jornada')
@section('page_title', 'Edições da Jornada')

@section('topbar_actions')
    <a href="{{ route('admin.edicoes.create') }}" class="btn btn-sm btn-cta">
        <i class="bi bi-plus-circle"></i> Nova edição
    </a>
@endsection

@section('content')
    <div class="panel">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Edição</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>Estado</th>
                        <th>Inscrições</th>
                        <th>Submissões</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($edicoes as $e)
                    <tr>
                        <td>
                            <strong>{{ $e->numero_romano }} · {{ $e->nome_curto ?? $e->nome }}</strong>
                            <small class="d-block text-muted">{{ $e->slug }}</small>
                        </td>
                        <td><small>{{ \App\Models\Edicao::TIPOS[$e->tipo] ?? $e->tipo }}</small></td>
                        <td><small>{{ $e->data_extenso }}</small></td>
                        <td>
                            @if ($e->status === 'actual')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Actual</span>
                            @elseif ($e->status === 'futura')
                                <span class="badge bg-info"><i class="bi bi-clock"></i> Futura</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-archive"></i> Passada</span>
                            @endif
                        </td>
                        <td>{{ $e->inscricoes()->count() }}</td>
                        <td>{{ $e->submissoes()->count() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.edicoes.show', $e) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.edicoes.edit', $e) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            @if ($e->status !== 'actual')
                                <form method="POST" action="{{ route('admin.edicoes.activar', $e) }}" class="d-inline"
                                      onsubmit="return confirm('Definir {{ $e->nome_curto }} como edição actual?');">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success" title="Definir como actual">
                                        <i class="bi bi-star"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Sem edições registadas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $edicoes->links() }}
    </div>
@endsection
