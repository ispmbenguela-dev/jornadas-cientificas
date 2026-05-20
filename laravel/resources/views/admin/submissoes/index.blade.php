@extends('layouts.admin')

@section('title', 'Submissões')
@section('page_title', 'Submissões')

@section('content')
    <div class="panel">
        <form method="GET" class="filter-row">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Título, autor ou e-mail" />
            </div>
            <select name="estado" class="form-select">
                <option value="">Todos os estados</option>
                @foreach (['pendente', 'admitida', 'rejeitada'] as $e)
                    <option value="{{ $e }}" @selected(request('estado') === $e)>{{ ucfirst($e) }}</option>
                @endforeach
            </select>
            <button class="btn btn-cta btn-sm"><i class="bi bi-funnel"></i> Filtrar</button>
        </form>

        <div class="table-responsive mt-3">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Título</th>
                        <th>Autor principal</th>
                        <th>Instituição</th>
                        <th>Área</th>
                        <th>Estado</th>
                        <th>Ficheiro</th>
                        <th>Submetido</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($submissoes as $s)
                    <tr>
                        <td>#{{ str_pad($s->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td><strong>{{ \Illuminate\Support\Str::limit($s->titulo, 60) }}</strong></td>
                        <td>{{ $s->autor_principal }}<small class="d-block text-muted">{{ $s->email }}</small></td>
                        <td>{{ $s->instituicao }}</td>
                        <td><small>{{ $s->area_tematica ?: '—' }}</small></td>
                        <td>@include('admin._estado_badge', ['estado' => $s->estado])</td>
                        <td>
                            <a href="{{ asset('storage/' . $s->ficheiro_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-arrow-down"></i>
                            </a>
                        </td>
                        <td><small>{{ $s->created_at->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('admin.submissoes.show', $s) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-muted text-center py-4">Sem submissões.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $submissoes->links() }}
    </div>
@endsection
