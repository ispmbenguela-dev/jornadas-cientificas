@php
    $numero = str_pad($inscricao->id, 4, '0', STR_PAD_LEFT);
    $cfg = match ($inscricao->estado) {
        'confirmada' => [
            'banner_grad'  => 'linear-gradient(135deg,#0f4c81,#10b981)',
            'icon'         => '✓',
            'titulo'       => 'Inscrição confirmada',
            'subtitulo'    => 'Tem o seu lugar garantido na XI Jornada.',
            'msg_principal' => 'Temos o prazer de confirmar a sua inscrição na XI Jornada Científico-Metodológica Geral do ISPM. O seu lugar está garantido.',
            'note_kind'    => null,
        ],
        'rejeitada' => [
            'banner_grad'  => 'linear-gradient(135deg,#7f1d1d,#dc2626)',
            'icon'         => '✕',
            'titulo'       => 'Inscrição não validada',
            'subtitulo'    => 'A sua inscrição não pôde ser aceite.',
            'msg_principal' => 'Lamentamos informar que a Comissão Científica não pôde validar a sua inscrição. Pode encontrar abaixo a justificação e contactar-nos caso pretenda corrigir os dados ou reabrir o processo.',
            'note_kind'    => 'warn',
        ],
        'pendente' => [
            'banner_grad'  => 'linear-gradient(135deg,#0f4c81,#f37021)',
            'icon'         => '…',
            'titulo'       => 'Inscrição em análise',
            'subtitulo'    => 'Vamos rever os seus dados.',
            'msg_principal' => 'A sua inscrição encontra-se novamente em análise pela Comissão Científica. Iremos contactá-lo(a) assim que houver uma decisão.',
            'note_kind'    => 'info',
        ],
        default => [
            'banner_grad'  => 'linear-gradient(135deg,#0f4c81,#f37021)',
            'icon'         => 'i',
            'titulo'       => 'Inscrição actualizada',
            'subtitulo'    => 'Houve uma alteração no estado da sua inscrição.',
            'msg_principal' => 'A sua inscrição foi actualizada pela Comissão Científica.',
            'note_kind'    => null,
        ],
    };
@endphp
<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $cfg['titulo'] }} — XI Jornada ISPM</title>
    <style>
        body { margin:0; padding:0; background:#f4f6fa; font-family: 'Segoe UI', Arial, sans-serif; color:#1f2937; }
        .wrap { max-width:640px; margin:0 auto; padding:24px 16px; }
        .card { background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 16px rgba(15,76,129,0.08); }
        .header { background: {{ $cfg['banner_grad'] }}; color:#fff; padding:28px 32px; text-align:center; }
        .header .icon { font-size:36px; line-height:1; margin-bottom:10px; }
        .header h1 { margin:0 0 4px; font-size:22px; font-weight:800; letter-spacing:-0.3px; }
        .header p  { margin:0; opacity:0.92; font-size:13px; }
        .body { padding:28px 32px; }
        h2 { font-size:16px; margin:18px 0 10px; color:#0f4c81; }
        p  { font-size:14px; line-height:1.55; margin:0 0 12px; }
        .ref { display:inline-block; background:#f3f4f6; color:#0f4c81; font-family: 'Consolas','Courier New',monospace; padding:6px 12px; border-radius:8px; font-size:14px; font-weight:700; }
        table.details { width:100%; border-collapse:collapse; margin:6px 0 14px; }
        table.details td { padding:8px 0; border-bottom:1px solid #eef0f5; font-size:14px; vertical-align:top; }
        table.details td.k { color:#6b7280; width:38%; }
        table.details td.v { color:#1f2937; font-weight:600; }
        .badge { display:inline-block; padding:3px 9px; border-radius:999px; font-size:12px; font-weight:700; }
        .badge-ok       { background:#d1fae5; color:#065f46; }
        .badge-warn     { background:#fef3c7; color:#92400e; }
        .badge-error    { background:#fee2e2; color:#991b1b; }
        .badge-info     { background:#dbeafe; color:#1e3a8a; }
        .badge-na       { background:#f3f4f6; color:#6b7280; }
        .mc-list { list-style:none; padding:0; margin:6px 0 14px; }
        .mc-list li { background:#fff7f0; border-left:3px solid #f37021; padding:8px 12px; margin-bottom:6px; border-radius:6px; font-size:13px; line-height:1.45; }
        .note-info   { background:#dbeafe; border-left:4px solid #2563eb; padding:12px 16px; border-radius:8px; font-size:13px; line-height:1.5; margin:12px 0; color:#1e3a8a; }
        .note-warn   { background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:8px; font-size:13px; line-height:1.5; margin:12px 0; color:#92400e; }
        .note-ok     { background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:8px; font-size:13px; line-height:1.5; margin:12px 0; color:#065f46; }
        .footer { padding:18px 32px; background:#f9fafb; border-top:1px solid #eef0f5; font-size:12px; color:#6b7280; text-align:center; }
        a { color:#0f4c81; }
        .state-change { background:#f7faff; border:1px solid #dde7f4; border-radius:10px; padding:12px 16px; margin:8px 0 18px; font-size:13px; }
        .state-change strong { color:#0f4c81; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header">
                <div class="icon">{{ $cfg['icon'] }}</div>
                <h1>{{ $cfg['titulo'] }}</h1>
                <p>{{ $cfg['subtitulo'] }}</p>
            </div>

            <div class="body">
                <p>Olá <strong>{{ $inscricao->nome }}</strong>,</p>

                <p>{{ $cfg['msg_principal'] }}</p>

                <div class="state-change">
                    Inscrição <span class="ref">#{{ $numero }}</span> ·
                    Estado actualizado de
                    <strong>{{ ucfirst($estadoAnterior) }}</strong>
                    para
                    @if ($inscricao->estado === 'confirmada')
                        <span class="badge badge-ok">{{ ucfirst($inscricao->estado) }}</span>
                    @elseif ($inscricao->estado === 'rejeitada')
                        <span class="badge badge-error">{{ ucfirst($inscricao->estado) }}</span>
                    @else
                        <span class="badge badge-info">{{ ucfirst($inscricao->estado) }}</span>
                    @endif
                </div>

                @if ($inscricao->estado === 'confirmada')
                    <div class="note-ok">
                        <strong>O que se segue:</strong> apresente-se nos dias <strong>11 e 12 de Junho de 2026</strong>
                        no ISPM (Av. Aires de Almeida Santos), a partir das 08h30.
                        @if ($inscricao->mini_cursos_count > 0)
                            Não se esqueça do(s) mini-curso(s) que escolheu — detalhes em baixo.
                        @endif
                    </div>
                @endif

                @if ($inscricao->estado === 'rejeitada' && $inscricao->observacoes)
                    <h2>Motivo / observações</h2>
                    <div class="note-warn">{!! nl2br(e($inscricao->observacoes)) !!}</div>
                @elseif ($inscricao->observacoes)
                    <h2>Observações da Comissão</h2>
                    <div class="note-info">{!! nl2br(e($inscricao->observacoes)) !!}</div>
                @endif

                <h2>Detalhes da inscrição</h2>
                <table class="details">
                    <tr><td class="k">Nome</td><td class="v">{{ $inscricao->nome }}</td></tr>
                    <tr><td class="k">E-mail</td><td class="v">{{ $inscricao->email }}</td></tr>
                    <tr><td class="k">Categoria</td>
                        <td class="v">
                            {{ $inscricao->categoria_label }}
                            @if ($inscricao->is_docente_ispm)
                                <span class="badge badge-info">Docente ISPM ✓</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="k">Modalidade</td><td class="v">{{ $inscricao->modalidade_label }}</td></tr>
                    <tr><td class="k">Valor</td>
                        <td class="v">
                            @if ($inscricao->valor_kz === 0)
                                <span class="badge badge-ok">Gratuito</span>
                            @else
                                {{ $inscricao->valor_formatado }}
                            @endif
                        </td>
                    </tr>
                    @if ($inscricao->referencia_pagamento)
                        <tr><td class="k">Referência</td><td class="v">{{ $inscricao->referencia_pagamento }}</td></tr>
                    @endif
                </table>

                @if ($inscricao->mini_cursos_count > 0)
                    <h2>Mini-curso{{ $inscricao->mini_cursos_count > 1 ? 's' : '' }}</h2>
                    <ul class="mc-list">
                        @foreach ($inscricao->mini_cursos_list as $linha)
                            <li>{{ $linha }}</li>
                        @endforeach
                    </ul>
                @endif

                @if ($inscricao->estado === 'rejeitada')
                    <p>
                        Se considera que houve engano, responda a este e-mail ou contacte directamente
                        a Comissão Científica:
                    </p>
                @else
                    <p>Para qualquer questão, contacte a Comissão Científica:</p>
                @endif

                <p>
                    <strong>922 606 147</strong> · <strong>957 360 076</strong> · <strong>922 140 990</strong><br>
                    <a href="mailto:vp.cientifica@ispmaravilha.com">vp.cientifica@ispmaravilha.com</a>
                </p>

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