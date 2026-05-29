<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submissão #{{ str_pad($submissao->id, 4, '0', STR_PAD_LEFT) }} — XI Jornada ISPM</title>
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
        .badge-info { background:#dbeafe; color:#1e3a8a; }
        .note { background:#dbeafe; border-left:4px solid #2563eb; padding:12px 16px; border-radius:8px; font-size:13px; line-height:1.5; color:#1e3a8a; margin:8px 0 16px; }
        .footer { padding:18px 32px; background:#f9fafb; border-top:1px solid #eef0f5; font-size:12px; color:#6b7280; text-align:center; }
        a { color:#0f4c81; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header">
                <h1>Submissão recebida</h1>
                <p>XI Jornada Científico-Metodológica Geral · 11 e 12 de Junho de 2026</p>
            </div>

            <div class="body">
                <p>Olá <strong>{{ $submissao->autor_principal }}</strong>,</p>

                <p>
                    Recebemos o seu trabalho com o número
                    <span class="ref">#{{ str_pad($submissao->id, 4, '0', STR_PAD_LEFT) }}</span>.
                    A Comissão Científica irá avaliar a submissão e comunicar a decisão
                    (admissão ou não) por este e-mail.
                </p>

                <h2>Detalhes da submissão</h2>
                <table class="details">
                    <tr><td class="k">Título</td><td class="v">{{ $submissao->titulo }}</td></tr>
                    <tr><td class="k">Autor principal</td><td class="v">{{ $submissao->autor_principal }}</td></tr>
                    @if ($submissao->coautores)
                        <tr><td class="k">Coautores</td><td class="v">{{ $submissao->coautores }}</td></tr>
                    @endif
                    <tr><td class="k">E-mail</td><td class="v">{{ $submissao->email }}</td></tr>
                    @if ($submissao->telefone)
                        <tr><td class="k">Telefone</td><td class="v">{{ $submissao->telefone }}</td></tr>
                    @endif
                    <tr><td class="k">Instituição</td><td class="v">{{ $submissao->instituicao }}</td></tr>
                    @if ($submissao->area_tematica)
                        <tr><td class="k">Área temática</td><td class="v">{{ $submissao->area_tematica }}</td></tr>
                    @endif
                    @if ($submissao->ficheiro_original)
                        <tr><td class="k">Ficheiro</td><td class="v">{{ $submissao->ficheiro_original }}</td></tr>
                    @endif
                    <tr><td class="k">Estado</td>
                        <td class="v"><span class="badge badge-info">{{ ucfirst($submissao->estado) }}</span></td>
                    </tr>
                </table>

                <div class="note">
                    <strong>Próximo passo:</strong> guarde o número de submissão
                    <strong>#{{ str_pad($submissao->id, 4, '0', STR_PAD_LEFT) }}</strong>.
                    Iremos contactá-lo(a) por este e-mail assim que houver uma decisão da
                    Comissão Científica.
                </div>

                <p style="margin-top:20px;">
                    Para qualquer dúvida, contacte a Comissão Científica:<br>
                    <strong>922 606 147</strong> · <strong>957 360 076</strong> · <strong>922 140 990</strong><br>
                    <a href="mailto:vp.cientifica@ispmaravilha.com">vp.cientifica@ispmaravilha.com</a>
                </p>

                <p>Agradecemos a sua participação.</p>
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
