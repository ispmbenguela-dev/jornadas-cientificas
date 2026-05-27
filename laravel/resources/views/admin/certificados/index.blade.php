@extends('layouts.admin')

@section('title', 'Certificados')
@section('page_title', 'Certificados')

@section('topbar_actions')
    <a href="{{ route('admin.certificados.create') }}" class="btn btn-sm btn-cta">
        <i class="bi bi-plus-circle"></i> Emitir manualmente
    </a>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon"><i class="bi bi-patch-check"></i></div>
                <div><span>Total emitidos</span><strong>{{ $stats['total'] }}</strong></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card kpi-warn">
                <div class="kpi-icon"><i class="bi bi-file-earmark"></i></div>
                <div><span>Aguardando envio</span><strong>{{ $stats['emitidos'] }}</strong></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card kpi-success">
                <div class="kpi-icon"><i class="bi bi-send-check"></i></div>
                <div><span>Já enviados</span><strong>{{ $stats['enviados'] }}</strong></div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="kpi-card kpi-accent">
                <div class="kpi-icon"><i class="bi bi-people"></i></div>
                <div>
                    <span>Inscrições confirmadas</span>
                    <strong>{{ $stats['inscricoes_confirmadas'] }}</strong>
                    <small class="d-block text-muted">{{ $stats['inscricoes_com_certificado'] }} com certificado</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="panel h-100">
                <h4 class="mb-2"><i class="bi bi-people"></i> Participantes</h4>
                <p class="small text-muted mb-3">
                    Gera certificados de <strong>participante</strong> para inscrições com estado <em>confirmada</em>
                    que ainda não tenham certificado.<br>
                    <strong>{{ $stats['inscricoes_com_certificado'] }} / {{ $stats['inscricoes_confirmadas'] }}</strong> já emitidos.
                </p>
                <form method="POST" action="{{ route('admin.certificados.gerar_lote') }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Data do evento</label>
                        <input type="date" name="data_evento" value="2026-06-12" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-cta w-100">
                            <i class="bi bi-lightning"></i> Gerar participantes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="panel h-100">
                <h4 class="mb-2"><i class="bi bi-mic"></i> Prelectores (Comunicações)</h4>
                <p class="small text-muted mb-3">
                    Gera certificados de <strong>prelector de comunicação livre</strong> para todas as submissões
                    com estado <em>admitida</em>, usando o autor principal e o título.<br>
                    <strong>{{ $stats['submissoes_com_certificado'] }} / {{ $stats['submissoes_admitidas'] }}</strong> já emitidos.
                </p>
                <form method="POST" action="{{ route('admin.certificados.gerar_prelectores') }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Data do evento</label>
                        <input type="date" name="data_evento" value="2026-06-12" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-cta w-100">
                            <i class="bi bi-lightning"></i> Gerar prelectores
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="panel mb-3">
        <h4 class="mb-2"><i class="bi bi-envelope-paper"></i> Envio em lote</h4>
        <p class="small text-muted mb-3">
            Envia por e-mail todos os certificados ainda no estado <em>emitido</em>
            ({{ $stats['emitidos'] }} pendentes). Apenas certificados ligados a uma inscrição ou
            submissão (com e-mail) serão enviados.
        </p>
        <form method="POST" action="{{ route('admin.certificados.enviar_todos') }}"
              onsubmit="return confirm('Enviar todos os {{ $stats['emitidos'] }} certificados pendentes por e-mail?');"
              class="row g-2 align-items-end">
            @csrf
            <div class="col-md-4">
                <label class="form-label">Filtrar por tipo (opcional)</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($tipos as $k => $l)
                        <option value="{{ $k }}">{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-cta w-100" {{ $stats['emitidos'] === 0 ? 'disabled' : '' }}>
                    <i class="bi bi-send-check"></i> Enviar todos pendentes
                </button>
            </div>
        </form>
    </div>

    <div class="panel">
        <form method="GET" class="filter-row">
            <div class="search-wrap">
                <i class="bi bi-search"></i>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nome, código ou tema">
            </div>
            <select name="tipo" class="form-select">
                <option value="">Todos os tipos</option>
                @foreach ($tipos as $k => $l)
                    <option value="{{ $k }}" @selected(request('tipo') === $k)>{{ $l }}</option>
                @endforeach
            </select>
            <select name="estado" class="form-select">
                <option value="">Todos os estados</option>
                @foreach (['emitido' => 'Emitido', 'enviado' => 'Enviado', 'descarregado' => 'Descarregado'] as $k => $l)
                    <option value="{{ $k }}" @selected(request('estado') === $k)>{{ $l }}</option>
                @endforeach
            </select>
            <button class="btn btn-cta btn-sm"><i class="bi bi-funnel"></i> Filtrar</button>
        </form>

        <div class="table-responsive mt-3">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Tema</th>
                        <th>Data evento</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($certificados as $c)
                    <tr>
                        <td><code>{{ $c->codigo }}</code></td>
                        <td>
                            <strong>{{ $c->nome }}</strong>
                            @if ($c->email_destino)
                                <small class="d-block text-muted">{{ $c->email_destino }}</small>
                            @endif
                        </td>
                        <td>{{ $c->tipo_label }}</td>
                        <td><small>{{ $c->tema ? '"' . \Illuminate\Support\Str::limit($c->tema, 60) . '"' : '—' }}</small></td>
                        <td><small>{{ $c->data_evento?->format('d/m/Y') }}</small></td>
                        <td>
                            @if ($c->estado === 'enviado')
                                <span class="badge bg-success"><i class="bi bi-send-check"></i> Enviado</span>
                            @elseif ($c->estado === 'descarregado')
                                <span class="badge bg-info"><i class="bi bi-download"></i> Descarregado</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Emitido</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.certificados.show', $c) }}" class="btn btn-sm btn-outline-secondary" title="Ver">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.certificados.download', $c) }}" class="btn btn-sm btn-outline-primary" title="PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>
                            @if ($c->email_destino)
                                <form method="POST" action="{{ route('admin.certificados.enviar', $c) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success" title="Enviar por e-mail">
                                        <i class="bi bi-envelope"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Sem certificados emitidos.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $certificados->links() }}
    </div>
@endsection