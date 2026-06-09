@extends('layouts.admin')

@section('title', 'Avaliações')
@section('page_title', 'Avaliações da Jornada')

@section('content')

    {{-- Estatísticas gerais --}}
    @if ($stats['total'] > 0)
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="panel text-center">
                <div class="fs-2 fw-bold text-accent">{{ $stats['total'] }}</div>
                <small class="text-muted">Respostas totais</small>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="panel text-center">
                <div class="fs-2 fw-bold" style="color: {{ $stats['media_global'] >= 4 ? '#198754' : ($stats['media_global'] >= 3 ? '#ffc107' : '#dc3545') }}">
                    {{ number_format($stats['media_global'], 1) }}<small class="fs-6 text-muted">/5</small>
                </div>
                <small class="text-muted">Média global</small>
            </div>
        </div>
        @foreach ($stats['secoes'] as $key => $media)
        <div class="col-sm-6 col-xl-3">
            <div class="panel text-center">
                <div class="fs-4 fw-bold" style="color: {{ $media >= 4 ? '#198754' : ($media >= 3 ? '#fd7e14' : '#dc3545') }}">
                    {{ number_format($media, 1) }}<small class="fs-6 text-muted">/5</small>
                </div>
                <small class="text-muted">{{ $secoes[$key]['titulo'] }}</small>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="panel">
        {{-- Filtros --}}
        <form method="GET" class="filter-row mb-3">
            <select name="edicao_id" class="form-select">
                <option value="">Todas as edições</option>
                @foreach ($edicoes as $e)
                    <option value="{{ $e->id }}" @selected(request('edicao_id') == $e->id)>{{ $e->nome_curto }}</option>
                @endforeach
            </select>
            <select name="categoria" class="form-select">
                <option value="">Todas as categorias</option>
                @foreach ($categorias as $k => $l)
                    <option value="{{ $k }}" @selected(request('categoria') === $k)>{{ $l }}</option>
                @endforeach
            </select>
            <select name="sexo" class="form-select">
                <option value="">Todos os sexos</option>
                <option value="M" @selected(request('sexo') === 'M')>Masculino</option>
                <option value="F" @selected(request('sexo') === 'F')>Feminino</option>
            </select>
            <button class="btn btn-cta btn-sm"><i class="bi bi-funnel"></i> Filtrar</button>
            @if (request()->hasAny(['edicao_id','categoria','sexo']))
                <a href="{{ route('admin.avaliacoes.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i> Limpar
                </a>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Sexo</th>
                        <th>Idade</th>
                        <th>Categoria</th>
                        <th>Edição</th>
                        <th>Média</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($avaliacoes as $a)
                    @php $media = $a->mediaGlobal(); @endphp
                    <tr>
                        <td><small class="text-muted">#{{ $a->id }}</small></td>
                        <td><small>{{ $a->created_at->format('d/m/Y H:i') }}</small></td>
                        <td>{{ $a->sexo_label }}</td>
                        <td>{{ $a->idade }}</td>
                        <td>{{ $a->categoria_label }}</td>
                        <td>
                            @if ($a->edicao)
                                <small>{{ $a->edicao->nome_curto }}</small>
                            @else
                                <small class="text-muted fst-italic">Sem edição</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge fs-6"
                                  style="background: {{ $media >= 4 ? '#198754' : ($media >= 3 ? '#fd7e14' : '#dc3545') }}">
                                {{ number_format($media, 1) }}
                            </span>
                        </td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('admin.avaliacoes.show', $a) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.avaliacoes.destroy', $a) }}"
                                  onsubmit="return confirm('Remover esta resposta?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-muted text-center py-4">Sem respostas registadas.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $avaliacoes->links() }}
    </div>
@endsection