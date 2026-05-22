<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Inscrições — XI Jornada ISPM</title>
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
        .badge-pendente   { background: #fef3c7; color: #92400e; }
        .badge-confirmada { background: #d1fae5; color: #065f46; }
        .badge-rejeitada  { background: #fee2e2; color: #991b1b; }
        .totais { margin-top: 12px; font-size: 10px; }
        .totais td { border: none; padding: 2px 6px; }
        .footer { position: fixed; bottom: 6px; left: 0; right: 0; text-align: center; font-size: 8px; color: #9ca3af; }
        .num { text-align: right; white-space: nowrap; }
        .small { color: #6b7280; font-size: 8.5px; }
    </style>
</head>
<body>
    <h1>Inscrições — XI Jornada Científico-Metodológica ISPM</h1>
    <div class="meta">Gerado em {{ now()->format('d/m/Y H:i') }} · {{ $inscricoes->count() }} registo(s)</div>

    @if ($filtros['estado'] || $filtros['categoria'] || $filtros['q'])
        <div class="filters">
            <strong>Filtros aplicados:</strong>
            @if ($filtros['estado'])    Estado = <em>{{ ucfirst($filtros['estado']) }}</em> · @endif
            @if ($filtros['categoria']) Categoria = <em>{{ $categorias[$filtros['categoria']] ?? $filtros['categoria'] }}</em> · @endif
            @if ($filtros['q'])         Pesquisa = <em>"{{ $filtros['q'] }}"</em> @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome / Contacto</th>
                <th>Instituição</th>
                <th>Categoria</th>
                <th>Modalidade</th>
                <th class="num">Valor (Kz)</th>
                <th>Estado</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($inscricoes as $i)
            <tr>
                <td>#{{ str_pad($i->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <strong>{{ $i->nome }}</strong>
                    <div class="small">{{ $i->email }} · {{ $i->telefone }}</div>
                </td>
                <td>{{ $i->instituicao ?: '—' }}</td>
                <td>{{ $i->categoria_label }}</td>
                <td>
                    {{ $i->modalidade_label }}
                    @if ($i->mini_cursos_count > 0)
                        <div class="small">
                            <strong>{{ $i->mini_cursos_count }} mini-curso(s):</strong>
                            @foreach ($i->mini_cursos_list as $linha)
                                <div>• {{ $linha }}</div>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="num">{{ number_format($i->valor_kz, 0, ',', '.') }}</td>
                <td><span class="badge badge-{{ $i->estado }}">{{ ucfirst($i->estado) }}</span></td>
                <td>{{ optional($i->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="8" style="text-align:center; color:#6b7280; padding:16px;">Sem inscrições para os filtros seleccionados.</td></tr>
        @endforelse
        </tbody>
    </table>

    @if ($inscricoes->isNotEmpty())
        <table class="totais">
            <tr>
                <td><strong>Total de registos:</strong></td>
                <td>{{ $inscricoes->count() }}</td>
                <td><strong>Receita confirmada:</strong></td>
                <td class="num">{{ number_format($totalValor, 0, ',', '.') }} Kz</td>
            </tr>
        </table>
    @endif

    <div class="footer">ISPM · XI Jornada Científico-Metodológica · 11 e 12 de Junho de 2026</div>
</body>
</html>
