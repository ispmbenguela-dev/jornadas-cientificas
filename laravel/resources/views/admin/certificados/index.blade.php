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

    <div class="panel mb-3">
        <h4 class="mb-2"><i class="bi bi-person-check"></i> Enviar por inscrito</h4>
        <p class="small text-muted mb-3">
            Pesquise inscrições confirmadas, seleccione uma ou mais e envie o certificado de participante.
            Se ainda não existir certificado para o inscrito, será gerado automaticamente.
        </p>

        <div class="row g-2 mb-3">
            <div class="col-md-7">
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="inscrito-search" class="form-control"
                           placeholder="Nome ou e-mail do inscrito (mín. 2 caracteres)…">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" id="inscrito-data-evento" value="2026-06-12" class="form-control" title="Data do evento (para gerar cert. se não existir)">
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <span class="text-muted small" id="inscrito-loading" style="display:none!important">
                    <i class="bi bi-hourglass-split"></i> A pesquisar…
                </span>
            </div>
        </div>

        <div id="inscrito-results" class="mb-3">
            <p class="text-muted small mb-0">Escreva no campo acima para pesquisar inscritos.</p>
        </div>

        <form id="form-enviar-inscritos" method="POST" action="{{ route('admin.certificados.enviar_inscritos') }}"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="data_evento" id="hidden-data-evento" value="2026-06-12">
            <div id="inscrito-ids-container"></div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-file-earmark-arrow-up"></i> Carregar certificado (PDF ou imagem)
                    <span class="text-muted fw-normal">— opcional</span>
                </label>
                <input type="file" name="certificado_pdf" id="certificado_pdf"
                       accept="application/pdf,image/png,image/jpeg,image/webp,image/gif,image/bmp,.pdf,.png,.jpg,.jpeg,.webp,.gif,.bmp" class="form-control">
                <div class="form-text" id="pdf-upload-hint">
                    Se carregar um ficheiro PDF ou imagem (PNG, JPG, JPEG, WEBP, GIF ou BMP), será usado como certificado para todos os inscritos seleccionados.
                    Sem ficheiro, o certificado é gerado automaticamente.
                </div>
                <div id="pdf-upload-preview" class="mt-2" style="display:none">
                    <span class="badge bg-primary"><i class="bi bi-file-earmark-arrow-up"></i> <span id="pdf-filename"></span></span>
                    <button type="button" id="btn-clear-pdf" class="btn btn-sm btn-link text-danger p-0 ms-1">remover</button>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <button type="submit" id="btn-enviar-inscritos" class="btn btn-cta" disabled>
                    <i class="bi bi-send"></i> Enviar seleccionados
                    (<span id="count-selected">0</span>)
                </button>
                <button type="button" id="btn-select-all" class="btn btn-sm btn-outline-secondary" style="display:none">
                    Seleccionar todos
                </button>
                <button type="button" id="btn-clear-sel" class="btn btn-sm btn-outline-secondary" style="display:none">
                    Limpar selecção
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
                            <a href="{{ route('admin.certificados.download', $c) }}" class="btn btn-sm btn-outline-primary" title="Descarregar certificado">
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

@push('scripts')
<script>
(function () {
    const searchInput   = document.getElementById('inscrito-search');
    const resultsDiv    = document.getElementById('inscrito-results');
    const idsContainer  = document.getElementById('inscrito-ids-container');
    const countSpan     = document.getElementById('count-selected');
    const btnEnviar     = document.getElementById('btn-enviar-inscritos');
    const btnSelectAll  = document.getElementById('btn-select-all');
    const btnClearSel   = document.getElementById('btn-clear-sel');
    const dataInput     = document.getElementById('inscrito-data-evento');
    const hiddenData    = document.getElementById('hidden-data-evento');
    const buscarUrl     = '{{ route('admin.certificados.buscar_inscritos') }}';

    dataInput.addEventListener('change', () => { hiddenData.value = dataInput.value; });

    let debounce;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounce);
        debounce = setTimeout(search, 350);
    });

    function search() {
        const q = searchInput.value.trim();
        if (q.length < 2) {
            resultsDiv.innerHTML = '<p class="text-muted small mb-0">Escreva pelo menos 2 caracteres para pesquisar.</p>';
            btnSelectAll.style.display = 'none';
            btnClearSel.style.display  = 'none';
            return;
        }

        resultsDiv.innerHTML = '<p class="text-muted small mb-0"><i class="bi bi-hourglass-split"></i> A pesquisar…</p>';

        fetch(buscarUrl + '?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(renderResults)
        .catch(() => {
            resultsDiv.innerHTML = '<p class="text-danger small mb-0">Erro ao pesquisar. Tente novamente.</p>';
        });
    }

    function renderResults(inscritos) {
        if (!inscritos.length) {
            resultsDiv.innerHTML = '<p class="text-muted small mb-0">Nenhum inscrito confirmado encontrado.</p>';
            btnSelectAll.style.display = 'none';
            btnClearSel.style.display  = 'none';
            updateSelection();
            return;
        }

        const badgeMap = {
            'enviado':      '<span class="badge bg-success"><i class="bi bi-send-check"></i> Enviado</span>',
            'descarregado': '<span class="badge bg-info"><i class="bi bi-download"></i> Descarregado</span>',
            'emitido':      '<span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Emitido</span>',
        };

        let html = '<div class="list-group list-group-flush border rounded" style="max-height:320px;overflow-y:auto">';
        inscritos.forEach(i => {
            const certBadge = i.tem_cert
                ? (badgeMap[i.cert_estado] ?? '<span class="badge bg-secondary">Emitido</span>')
                : '<span class="badge bg-secondary"><i class="bi bi-file-earmark-plus"></i> Será gerado</span>';

            html += `
            <label class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-2" style="cursor:pointer">
                <input type="checkbox" class="inscrito-cb form-check-input flex-shrink-0" value="${i.id}">
                <div class="flex-grow-1 overflow-hidden">
                    <strong class="d-block text-truncate">${escHtml(i.nome)}</strong>
                    <small class="text-muted">${escHtml(i.email)}</small>
                </div>
                <div class="flex-shrink-0">${certBadge}</div>
            </label>`;
        });
        html += '</div>';
        resultsDiv.innerHTML = html;

        document.querySelectorAll('.inscrito-cb').forEach(cb => {
            cb.addEventListener('change', updateSelection);
        });

        btnSelectAll.style.display = '';
        btnClearSel.style.display  = '';
        updateSelection();
    }

    function updateSelection() {
        const allCbs    = document.querySelectorAll('.inscrito-cb');
        const checked   = document.querySelectorAll('.inscrito-cb:checked');
        countSpan.textContent = checked.length;
        btnEnviar.disabled    = checked.length === 0;

        idsContainer.innerHTML = '';
        checked.forEach(cb => {
            const inp = document.createElement('input');
            inp.type  = 'hidden';
            inp.name  = 'inscricao_ids[]';
            inp.value = cb.value;
            idsContainer.appendChild(inp);
        });
    }

    btnSelectAll.addEventListener('click', () => {
        document.querySelectorAll('.inscrito-cb').forEach(cb => { cb.checked = true; });
        updateSelection();
    });

    btnClearSel.addEventListener('click', () => {
        document.querySelectorAll('.inscrito-cb').forEach(cb => { cb.checked = false; });
        updateSelection();
    });

    // --- Upload certificate preview ---
    const pdfInput   = document.getElementById('certificado_pdf');
    const pdfPreview = document.getElementById('pdf-upload-preview');
    const pdfNameEl  = document.getElementById('pdf-filename');
    const btnClearPdf = document.getElementById('btn-clear-pdf');

    pdfInput.addEventListener('change', () => {
        if (pdfInput.files.length) {
            pdfNameEl.textContent = pdfInput.files[0].name;
            pdfPreview.style.display = '';
        } else {
            pdfPreview.style.display = 'none';
        }
    });

    btnClearPdf.addEventListener('click', () => {
        pdfInput.value = '';
        pdfPreview.style.display = 'none';
    });

    // --- Form submit confirmation ---
    document.getElementById('form-enviar-inscritos').addEventListener('submit', function (e) {
        const count   = document.querySelectorAll('.inscrito-cb:checked').length;
        const temFicheiro  = pdfInput.files.length > 0;
        const pdfNote = temFicheiro ? `\nFicheiro: ${pdfInput.files[0].name}` : '\n(certificado gerado automaticamente)';
        if (!confirm(`Enviar certificados para ${count} inscrito(s)?${pdfNote}`)) {
            e.preventDefault();
        }
    });

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
})();
</script>
@endpush
