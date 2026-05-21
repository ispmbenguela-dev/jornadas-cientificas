<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Submissões — XI Jornada ISPM</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #1f2937; margin: 0; }
        h1 { font-size: 16px; margin: 0 0 4px; color: #0b3d91; }
        .meta { color: #6b7280; font-size: 9px; margin-bottom: 10px; }
        .filters { font-size: 9px; color: #374151; margin-bottom: 8px; }
        .filters strong { color: #111827; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 4px 6px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; font-size: 9px; text-transform: uppercase; letter-spacing: .03em; color: #374151; }
        tr:nth-child(even) td { background: #fafafa; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 10px; font-size: 8.5px; font-weight: 600; }
        .badge-pendente  { background: #fef3c7; color: #92400e; }
        .badge-admitida  { background: #d1fae5; color: #065f46; }
        .badge-rejeitada { background: #fee2e2; color: #991b1b; }
        .footer { position: fixed; bottom: 6px; left: 0; right: 0; text-align: center; font-size: 8px; color: #9ca3af; }
        .small { color: #6b7280; font-size: 8.5px; }
    </style>
</head>
<body>
    <h1>Submissões — XI Jornada Científico-Metodológica ISPM</h1>
    <div class="meta">Gerado em {{ now()->format('d/m/Y H:i') }} · {{ $submissoes->count() }} registo(s)</div>

    @if ($filtros['estado'] || $filtros['q'])
        <div class="filters">
            <strong>Filtros aplicados:</strong>
            @if ($filtros['estado']) Estado = <em>{{ ucfirst($filtros['estado']) }}</em> · @endif
            @if ($filtros['q'])      Pesquisa = <em>"{{ $filtros['q'] }}"</em> @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th>Título</th>
                <th>Autor / Contacto</th>
                <th>Instituição</th>
                <th>Área Temática</th>
                <th>Estado</th>
                <th>Submetido</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($submissoes as $s)
            <tr>
                <td>#{{ str_pad($s->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <strong>{{ $s->titulo }}</strong>
                    @if ($s->coautores)
                        <div class="small">Coautores: {{ \Illuminate\Support\Str::limit($s->coautores, 120) }}</div>
                    @endif
                </td>
                <td>
                    {{ $s->autor_principal }}
                    <div class="small">{{ $s->email }}@if($s->telefone) · {{ $s->telefone }}@endif</div>
                </td>
                <td>{{ $s->instituicao }}</td>
                <td>{{ $s->area_tematica ?: '—' }}</td>
                <td><span class="badge badge-{{ $s->estado }}">{{ ucfirst($s->estado) }}</span></td>
                <td>{{ optional($s->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align:center; color:#6b7280; padding:16px;">Sem submissões para os filtros seleccionados.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="footer">ISPM · XI Jornada Científico-Metodológica · 11 e 12 de Junho de 2026</div>
</body>
</html>
