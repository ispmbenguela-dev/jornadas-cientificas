<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscrição #{{ str_pad($inscricao->id, 4, '0', STR_PAD_LEFT) }} — XI Jornada ISPM</title>
    <style>
        body { margin:0; padding:0; background:#f4f6fa; font-family: 'Segoe UI', Arial, sans-serif; color:#1f2937; }
        .wrap { max-width:640px; margin:0 auto; padding:24px 16px; }
        .card { background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 16px rgba(15,76,129,0.08); }
        .header { background:linear-gradient(135deg,#0f4c81,#f37021); color:#fff; padding:28px 32px; }
        .header h1 { margin:0 0 4px; font-size:22px; font-weight:800; letter-spacing:-0.3px; }
        .header p  { margin:0; opacity:0.92; font-size:13px; }
        .body { padding:28px 32px; }
        h2 { font-size:17px; margin:0 0 12px; color:#0f4c81; }
        p  { font-size:14px; line-height:1.55; margin:0 0 12px; }
        .ref { display:inline-block; background:#f3f4f6; color:#0f4c81; font-family: 'Consolas','Courier New',monospace; padding:6px 12px; border-radius:8px; font-size:14px; font-weight:700; }
        table.details { width:100%; border-collapse:collapse; margin:8px 0 16px; }
        table.details td { padding:8px 0; border-bottom:1px solid #eef0f5; font-size:14px; vertical-align:top; }
        table.details td.k { color:#6b7280; width:38%; }
        table.details td.v { color:#1f2937; font-weight:600; }
        .badge { display:inline-block; padding:3px 9px; border-radius:999px; font-size:12px; font-weight:700; }
        .badge-ok       { background:#d1fae5; color:#065f46; }
        .badge-warn     { background:#fef3c7; color:#92400e; }
        .badge-info     { background:#dbeafe; color:#1e3a8a; }
        .badge-na       { background:#f3f4f6; color:#6b7280; }
        .mc-list { list-style:none; padding:0; margin:6px 0 0; }
        .mc-list li { background:#fff7f0; border-left:3px solid #f37021; padding:8px 12px; margin-bottom:6px; border-radius:6px; font-size:13px; line-height:1.45; }
        .bank { background:#f7faff; border:1px solid #dde7f4; border-radius:10px; padding:16px 20px; margin:8px 0 16px; }
        .bank h3 { margin:0 0 8px; font-size:14px; color:#0f4c81; }
        .bank dl { margin:0; }
        .bank dt { display:inline-block; width:110px; color:#6b7280; font-size:13px; }
        .bank dd { display:inline; margin:0; font-weight:600; font-size:13px; color:#1f2937; }
        .bank dd::after { content:""; display:block; height:6px; }
        .note { background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:8px; font-size:13px; line-height:1.5; }
        .footer { padding:18px 32px; background:#f9fafb; border-top:1px solid #eef0f5; font-size:12px; color:#6b7280; text-align:center; }
        a { color:#0f4c81; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header">
                <h1>Inscrição confirmada</h1>
                <p>XI Jornada Científico-Metodológica Geral · 11 e 12 de Junho de 2026</p>
            </div>

            <div class="body">
                <p>Olá <strong>{{ $inscricao->nome }}</strong>,</p>

                <p>
                    Recebemos a sua inscrição com o número
                    <span class="ref">#{{ str_pad($inscricao->id, 4, '0', STR_PAD_LEFT) }}</span>.
                    A Comissão Científica irá conferir os dados e o pagamento (se aplicável) e
                    enviar a confirmação final por este e-mail.
                </p>

                <h2>Detalhes da inscrição</h2>
                <table class="details">
                    <tr><td class="k">Nome</td><td class="v">{{ $inscricao->nome }}</td></tr>
                    <tr><td class="k">E-mail</td><td class="v">{{ $inscricao->email }}</td></tr>
                    <tr><td class="k">Telefone</td><td class="v">{{ $inscricao->telefone }}</td></tr>
                    @if ($inscricao->instituicao)
                        <tr><td class="k">Instituição</td><td class="v">{{ $inscricao->instituicao }}</td></tr>
                    @endif
                    <tr><td class="k">Categoria</td>
                        <td class="v">
                            {{ $inscricao->categoria_label }}
                            @if ($inscricao->is_docente_ispm)
                                <span class="badge badge-info">Docente ISPM ✓</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="k">Modalidade</td><td class="v">{{ $inscricao->modalidade_label }}</td></tr>
                </table>

                @if ($inscricao->mini_cursos_count > 0)
                    <h2>Mini-curso{{ $inscricao->mini_cursos_count > 1 ? 's' : '' }} seleccionado{{ $inscricao->mini_cursos_count > 1 ? 's' : '' }}</h2>
                    <ul class="mc-list">
                        @foreach ($inscricao->mini_cursos_list as $linha)
                            <li>{{ $linha }}</li>
                        @endforeach
                    </ul>
                @endif

                <h2>Pagamento</h2>
                <table class="details">
                    <tr><td class="k">Valor a pagar</td>
                        <td class="v">
                            @if ($inscricao->valor_kz === 0)
                                <span class="badge badge-ok">Gratuito</span>
                                @if ($inscricao->is_docente_ispm)
                                    <small style="display:block; color:#6b7280; font-weight:400; margin-top:4px;">
                                        Benefício de docente ISPM verificado.
                                    </small>
                                @endif
                            @else
                                {{ $inscricao->valor_formatado }}
                            @endif
                        </td>
                    </tr>

                    @if ($inscricao->valor_pago_informado !== null)
                        <tr><td class="k">Valor declarado</td>
                            <td class="v">{{ number_format($inscricao->valor_pago_informado, 2, ',', '.') }} Kz</td>
                        </tr>
                        <tr><td class="k">Referência</td>
                            <td class="v">{{ $inscricao->referencia_pagamento ?: '—' }}</td>
                        </tr>
                        <tr><td class="k">Validação</td>
                            <td class="v">
                                @if ($inscricao->validacao_pagamento === 'ok')
                                    <span class="badge badge-ok">{{ $inscricao->validacao_pagamento_label }}</span>
                                @elseif ($inscricao->validacao_pagamento === 'divergente')
                                    <span class="badge badge-warn">{{ $inscricao->validacao_pagamento_label }}</span>
                                @else
                                    <span class="badge badge-na">{{ $inscricao->validacao_pagamento_label }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>

                @if ($inscricao->valor_kz > 0 && !$inscricao->comprovativo_path)
                    <div class="note">
                        <strong>⚠ Comprovativo em falta:</strong> envie o comprovativo de pagamento por
                        e-mail para <a href="mailto:vp.cientifica@ispmaravilha.com">vp.cientifica@ispmaravilha.com</a>
                        indicando o número de inscrição <strong>#{{ $inscricao->id }}</strong>.
                    </div>
                @endif

                @if ($inscricao->validacao_pagamento === 'divergente')
                    <div class="note">
                        <strong>⚠ Divergência no valor:</strong> o valor declarado
                        ({{ number_format($inscricao->valor_pago_informado, 0, ',', '.') }} Kz) não
                        coincide com o total calculado
                        ({{ number_format($inscricao->valor_kz, 0, ',', '.') }} Kz). A Comissão
                        Científica irá conferir o seu comprovativo.
                    </div>
                @endif

                @if ($inscricao->valor_kz > 0)
                    <div class="bank">
                        <h3>Dados bancários para depósito/transferência</h3>
                        <dl>
                            <dt>Banco</dt><dd>BPC</dd>
                            <dt>IBAN</dt><dd style="font-family:'Consolas','Courier New',monospace;">0010.0455.0165.8843.0116.9</dd>
                            <dt>Beneficiário</dt><dd>Instituto Superior Politécnico Maravilha</dd>
                        </dl>
                    </div>
                @endif

                <p style="margin-top:20px;">
                    Para qualquer dúvida, contacte a Comissão Científica:<br>
                    <strong>922 606 147</strong> · <strong>957 360 076</strong> · <strong>922 140 990</strong><br>
                    <a href="mailto:vp.cientifica@ispmaravilha.com">vp.cientifica@ispmaravilha.com</a>
                </p>

                <p>Aguardamos a sua participação.</p>
                <p style="color:#6b7280; font-size:13px;">— Comissão Científica · ISPM Benguela</p>
            </div>

            <div class="footer">
                Este é um e-mail automático enviado por {{ config('app.name', 'ISPM') }}.
                Av. Aires de Almeida Santos · www.ispmaravilha.com
            </div>
        </div>
    </div>
</body>
</html>