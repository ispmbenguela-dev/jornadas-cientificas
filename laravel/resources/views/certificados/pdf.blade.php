<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Certificado {{ $certificado->codigo }}</title>
    <style>
        @page { margin: 0; size: A4 landscape; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; background: #fff; }

        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            background: #fff;
            overflow: hidden;
        }

        /* Triângulos decorativos */
        .tri { position: absolute; width: 0; height: 0; }
        .tri-orange { border-top: 32mm solid #f37021; border-right: 32mm solid transparent; }
        .tri-orange-down { border-bottom: 28mm solid #f37021; border-left: 28mm solid transparent; }
        .tri-blue   { border-top: 30mm solid #0f4c81; border-right: 30mm solid transparent; }
        .tri-blue-down { border-bottom: 26mm solid #0f4c81; border-left: 26mm solid transparent; }
        .tri-light  { border-top: 22mm solid #5b9bd5; border-right: 22mm solid transparent; }

        .tl-1 { top: 0;     left: 0;     border-top: 28mm solid #0f4c81; border-right: 28mm solid transparent; }
        .tl-2 { top: 28mm;  left: 0;     border-top: 22mm solid #f37021; border-right: 22mm solid transparent; }
        .tl-3 { top: 80mm;  left: 0;     border-bottom: 22mm solid #5b9bd5; border-right: 22mm solid transparent; }
        .tl-4 { top: 100mm; left: 0;     border-top: 24mm solid #f37021; border-right: 24mm solid transparent; }
        .bl-1 { bottom: 0;  left: 0;     border-bottom: 32mm solid #0f4c81; border-right: 32mm solid transparent; }
        .bl-2 { bottom: 0;  left: 30mm;  border-bottom: 22mm solid #f37021; border-left: 22mm solid transparent; }
        .bl-3 { bottom: 30mm; left: 0;   border-bottom: 18mm solid #5b9bd5; border-right: 18mm solid transparent; }
        .tr-1 { top: 0;     right: 0;    border-top: 24mm solid #f37021; border-left: 24mm solid transparent; }
        .tr-2 { top: 24mm;  right: 0;    border-top: 20mm solid #0f4c81; border-left: 20mm solid transparent; }
        .tr-3 { top: 50mm;  right: 0;    border-bottom: 18mm solid #5b9bd5; border-left: 18mm solid transparent; }
        .br-1 { bottom: 0;  right: 0;    border-bottom: 26mm solid #f37021; border-left: 26mm solid transparent; }

        .content { position: relative; z-index: 2; padding: 18mm 30mm 16mm; text-align: center; }

        .header {
            display: table;
            margin: 0 auto 4mm;
        }
        .header-cell { display: table-cell; vertical-align: middle; padding: 0 6px; }
        .logo-circle {
            width: 22mm; height: 22mm;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #0f4c81;
            color: #0f4c81;
            font-size: 7px;
            font-weight: 700;
            text-align: center;
            padding-top: 7mm;
            line-height: 1.1;
        }
        .header-text { text-align: left; line-height: 1.15; }
        .header-text .ispm { font-size: 14pt; color: #5b9bd5; font-weight: 700; letter-spacing: 0.5px; }
        .header-text .inst { font-size: 11pt; color: #0f4c81; font-weight: 700; }
        .header-text .decreto { font-size: 7.5pt; color: #6b7280; margin-top: 1mm; }

        .event-title {
            font-size: 20pt;
            font-weight: 800;
            color: #0f4c81;
            letter-spacing: 1px;
            margin: 4mm 0 1mm;
        }
        .event-sub {
            font-size: 10pt;
            color: #1f2937;
            margin-bottom: 4mm;
        }

        h1.certificado-title {
            font-size: 48pt;
            color: #0f4c81;
            font-weight: 800;
            letter-spacing: 6px;
            margin: 2mm 0 4mm;
        }

        .certifica { font-size: 13pt; color: #1f2937; margin-bottom: 4mm; }

        .nome {
            font-family: 'Times New Roman', Georgia, serif;
            font-style: italic;
            font-size: 30pt;
            color: #1f2937;
            margin-bottom: 5mm;
            line-height: 1.1;
        }

        .corpo {
            font-size: 11.5pt;
            color: #1f2937;
            line-height: 1.55;
            padding: 0 16mm;
            margin-bottom: 6mm;
        }
        .corpo strong { color: #0f4c81; }

        .local-data {
            font-size: 11pt;
            color: #6b7280;
            margin-bottom: 8mm;
        }

        .footer {
            display: table;
            width: 100%;
            margin-top: 4mm;
        }
        .footer-col { display: table-cell; vertical-align: top; }
        .col-l { width: 33%; text-align: left; }
        .col-c { width: 34%; text-align: center; }
        .col-r { width: 33%; text-align: right; }

        .seal {
            display: inline-block;
            width: 24mm; height: 24mm;
            border-radius: 50%;
            background: #f4b400;
            color: #fff;
            text-align: center;
            padding-top: 7mm;
            font-size: 9pt;
            font-weight: 800;
            border: 3px solid #ffe082;
            line-height: 1.2;
        }
        .seal small { font-size: 7pt; display: block; font-weight: 400; }

        .signature {
            border-top: 1px solid #6b7280;
            display: inline-block;
            padding-top: 2mm;
            min-width: 70mm;
            font-size: 10pt;
        }
        .signature .role { color: #0f4c81; font-weight: 700; letter-spacing: 1px; font-size: 9pt; margin-bottom: 1mm; }
        .signature .name { font-weight: 700; color: #1f2937; font-size: 11pt; }

        .codigo {
            position: absolute;
            bottom: 6mm;
            left: 30mm;
            font-size: 7.5pt;
            color: #6b7280;
            font-family: 'Courier New', monospace;
        }
        .verify {
            position: absolute;
            bottom: 6mm;
            right: 36mm;
            font-size: 7.5pt;
            color: #6b7280;
            text-align: right;
            max-width: 100mm;
        }
        .qr {
            position: absolute;
            bottom: 4mm;
            right: 6mm;
            width: 22mm;
            height: 22mm;
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 1.5mm;
        }
        .qr img { width: 100%; height: 100%; }
    </style>
</head>
<body>
    <div class="page">
        {{-- Triângulos decorativos --}}
        <span class="tri tl-1"></span>
        <span class="tri tl-2"></span>
        <span class="tri tl-3"></span>
        <span class="tri tl-4"></span>
        <span class="tri bl-1"></span>
        <span class="tri bl-2"></span>
        <span class="tri bl-3"></span>
        <span class="tri tr-1"></span>
        <span class="tri tr-2"></span>
        <span class="tri tr-3"></span>
        <span class="tri br-1"></span>

        <div class="content">
            <div class="header">
                <div class="header-cell">
                    <div class="logo-circle">ISPM<br>BENGUELA<br>ANGOLA</div>
                </div>
                <div class="header-cell header-text">
                    <div class="ispm">ISPM</div>
                    <div class="inst">Instituto Superior<br>Politécnico Maravilha</div>
                    <div class="decreto">Criado pelo Decreto Presidencial n.º 168/12, de 24 de Julho</div>
                </div>
            </div>

            <div class="event-title">XI JORNADA CIENTÍFICO-METODOLÓGICA</div>
            <div class="event-sub">11 e 12 de Junho de 2026 · ISPM Benguela</div>

            <h1 class="certificado-title">CERTIFICADO</h1>

            <p class="certifica">Certifica-se que o (a) Sr. (a):</p>

            <div class="nome">{{ $certificado->nome }}</div>

            <div class="corpo">{{ $certificado->corpo_texto }}</div>

            <div class="local-data">
                Benguela, {{ $certificado->data_evento->translatedFormat('d \d\e F \d\e Y') }}
            </div>

            <table class="footer" width="100%">
                <tr>
                    <td class="footer-col col-l">
                        <div style="padding-left:6mm;">
                            <strong style="color:#0f4c81; font-size:10pt;">{{ $certificado->tipo_label }}</strong><br>
                            <small style="color:#6b7280; font-size:8pt;">
                                Código: {{ $certificado->codigo }}
                            </small>
                        </div>
                    </td>
                    <td class="footer-col col-c">
                        <div class="seal">
                            ISPM
                            <small>DESDE 2013</small>
                        </div>
                    </td>
                    <td class="footer-col col-r">
                        <div style="padding-right:6mm;">
                            <div class="signature">
                                <div class="role">O PRESIDENTE</div>
                                <div class="name">JOSÉ JANUÁRIO, PhD.</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="codigo">Código de verificação: {{ $certificado->codigo }}</div>
        <div class="verify">
            Verifique em<br>
            <strong>{{ $certificado->verify_url }}</strong>
        </div>

        @if ($qr = $certificado->qr_data_uri)
            <div class="qr">
                <img src="{{ $qr }}" alt="QR de verificação">
            </div>
        @endif
    </div>
</body>
</html>