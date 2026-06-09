@extends('layouts.admin')

@section('title', 'Comissão Organizadora')
@section('page_title', 'Comissão Organizadora')

@section('topbar_actions')
    <a href="{{ route('admin.mco.create') }}" class="btn btn-sm btn-cta">
        <i class="bi bi-person-plus"></i> Adicionar membro
    </a>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">{{ $membros->total() }} membro(s) registado(s)</span>
        </div>

        @if ($membros->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people" style="font-size:2.5rem"></i>
                <p class="mt-2">Ainda não há membros da Comissão Organizadora registados.</p>
                <a href="{{ route('admin.mco.create') }}" class="btn btn-cta btn-sm">
                    <i class="bi bi-person-plus"></i> Adicionar primeiro membro
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Instituição</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($membros as $m)
                            <tr>
                                <td class="text-muted small">{{ str_pad($m->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td><strong>{{ $m->nome }}</strong></td>
                                <td>{{ $m->email }}</td>
                                <td>{{ $m->telefone }}</td>
                                <td>{{ $m->instituicao ?: '—' }}</td>
                                <td>
                                    @if ($m->estado === 'confirmada')
                                        <span class="badge bg-success">Confirmada</span>
                                    @elseif ($m->estado === 'rejeitada')
                                        <span class="badge bg-danger">Rejeitada</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.mco.edit', $m) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <form method="POST" action="{{ route('admin.mco.destroy', $m) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Remover {{ addslashes($m->nome) }} da Comissão Organizadora?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $membros->links() }}
        @endif
    </div>
@endsection
