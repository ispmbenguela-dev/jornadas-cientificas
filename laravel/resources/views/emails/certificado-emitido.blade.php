<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Certificado emitido — XI Jornada ISPM</title>
    <style>
        body { margin:0; padding:0; background:#f4f6fa; font-family: 'Segoe UI', Arial, sans-serif; color:#1f2937; }
        .wrap { max-width:640px; margin:0 auto; padding:24px 16px; }
        .card { background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 16px rgba(15,76,129,0.08); }
        .header { background:linear-gradient(135deg,#0f4c81,#f37021); color:#fff; padding:28px 32px; text-align:center; }
        .header h1 { margin:0 0 4px; font-size:22px; font-weight:800; letter-spacing:-0.3px; }
        .header p  { margin:0; opacity:0.92; font-size:13px; }
        .body { padding:28px 32px; }
        p  { font-size:14px; line-height:1.55; margin:0 0 12px; }
        .ref { display:inline-block; background:#f3f4f6; color:#0f4c81; font-family:'Consolas','Courier New',monospace; padding:6px 12px; border-radius:8px; font-size:14px; font-weight:700; }
        .verify { background:#f7faff; border:1px solid #dde7f4; border-radius:10px; padding:14px 18px; margin:12px 0; font-size:13px; }
        .footer { padding:18px 32px; background:#f9fafb; border-top:1px solid #eef0f5; font-size:12px; color:#6b7280; text-align:center; }
        a { color:#0f4c81; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="header">
                <h1>O seu certificado está pronto</h1>
                <p>XI Jornada Científico-Metodológica · ISPM Benguela</p>
            </div>

            <div class="body">
                <p>Olá <strong>{{ $certificado->nome }}</strong>,</p>

                <p>
                    Tem o prazer de receber em anexo o seu certificado de
                    <strong>{{ $certificado->tipo_label }}</strong> na XI Jornada Científico-Metodológica Geral
                    do ISPM, realizada nos dias 11 e 12 de Junho de 2026.
                </p>

                <p>Código do certificado: <span class="ref">{{ $certificado->codigo }}</span></p>

                @if ($certificado->tema)
                    <p><strong>Tema:</strong> "{{ $certificado->tema }}"</p>
                @endif

                <div class="verify">
                    <strong>Verificação online:</strong><br>
                    Pode validar a autenticidade do certificado em
                    <a href="{{ route('certificado.verify', $certificado->codigo) }}">
                        {{ route('certificado.verify', $certificado->codigo) }}
                    </a>
                </div>

                <p>Agradecemos a sua presença e contribuição na XI Jornada.</p>

                <p style="color:#6b7280; font-size:13px;">— Comissão Científica · ISPM Benguela</p>
            </div>

            <div class="footer">
                Este é um e-mail automático enviado por {{ config('app.name', 'ISPM') }}.<br>
                Av. Aires de Almeida Santos · www.ispmaravilha.com
            </div>
        </div>
    </div>
</body>
</html>