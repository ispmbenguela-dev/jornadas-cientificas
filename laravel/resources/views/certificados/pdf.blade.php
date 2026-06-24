<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Certificado {{ $certificado->codigo }}</title>
    <style>
        @page { margin: 0; size: A4 landscape; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; background: #fff; width: 297mm; height: 210mm; }

        .page {
            position: relative;
            width: 297mm;
            height: 210mm;
            background: #fff;
            overflow: hidden;
        }

        /* ── Marca de água central ── */
        .watermark {
            position: absolute;
            top: 38mm;
            left: 82mm;
            width: 133mm;
            height: auto;
            opacity: 0.055;
            z-index: 1;
        }

        /* ── Triângulos decorativos ── */
        .tri { position: absolute; width: 0; height: 0; z-index: 3; }

        /* Superior esquerdo */
        .tl-b { top: 0;    left: 0;    border-top: 34mm solid #0f4c81; border-right: 34mm solid transparent; }
        .tl-o { top: 30mm; left: 0;    border-top: 22mm solid #f37021; border-right: 22mm solid transparent; }

        /* Meio esquerdo */
        .ml-b { top: 84mm;  left: 0;   border-bottom: 22mm solid #0f4c81; border-right: 22mm solid transparent; }
        .ml-o { top: 104mm; left: 0;   border-top:    20mm solid #f37021; border-right: 20mm solid transparent; }

        /* Inferior esquerdo */
        .bl-b1 { bottom: 0;    left: 0;    border-bottom: 36mm solid #0f4c81; border-right: 36mm solid transparent; }
        .bl-s  { bottom: 34mm; left: 0;    border-bottom: 18mm solid #5b9bd5; border-right: 18mm solid transparent; }
        .bl-o  { bottom: 0;    left: 34mm; border-bottom: 24mm solid #f37021; border-left: 24mm solid transparent; }
        .bl-b2 { bottom: 0;    left: 55mm; border-bottom: 16mm solid #0f4c81; border-left: 16mm solid transparent; }

        /* Superior direito */
        .tr-o { top: 0;    right: 0; border-top: 26mm solid #f37021; border-left: 26mm solid transparent; }
        .tr-b { top: 23mm; right: 0; border-top: 22mm solid #0f4c81; border-left: 22mm solid transparent; }
        .tr-s { top: 46mm; right: 0; border-bottom: 16mm solid #5b9bd5; border-left: 16mm solid transparent; }

        /* Inferior direito */
        .br-o { bottom: 0; right: 0; border-bottom: 28mm solid #f37021; border-left: 28mm solid transparent; }

        /* ── Layout principal ── */
        .content {
            position: relative;
            z-index: 2;
            width: 297mm;
            padding: 7mm 38mm 6mm;
            text-align: center;
        }

        /* Cabeçalho */
        .hdr-table { margin: 0 auto 4mm; display: table; }
        .hdr-logo  { display: table-cell; vertical-align: middle; padding-right: 5mm; }
        .hdr-sep   { display: table-cell; vertical-align: middle; padding: 0 4mm; }
        .hdr-sep-line { display: inline-block; width: 0.4mm; height: 14mm; background: #cbd5e1; }
        .hdr-txt   { display: table-cell; vertical-align: middle; text-align: left; }
        .hdr-logo img { height: 20mm; width: auto; }
        .h-ispm    { font-size: 20pt; font-weight: 800; color: #5b9bd5; letter-spacing: 1px; line-height: 1; }
        .h-inst    { font-size: 10pt; font-weight: 700; color: #0f4c81; line-height: 1.3; margin-top: 0.5mm; }
        .h-dec     { font-size: 6.8pt; color: #9ca3af; margin-top: 1mm; }

        /* Título */
        h1.ctitle {
            font-size: 56pt;
            font-weight: 800;
            color: #0f4c81;
            letter-spacing: 5px;
            line-height: 1;
            margin: 2mm 0 3mm;
        }

        .certifica { font-size: 11pt; color: #374151; margin-bottom: 2mm; }

        .nome {
            font-family: 'Times New Roman', Georgia, serif;
            font-style: italic;
            font-size: 28pt;
            color: #111827;
            line-height: 1.15;
            margin-bottom: 3.5mm;
        }

        .corpo {
            font-size: 9.8pt;
            color: #1f2937;
            line-height: 1.6;
            padding: 0 4mm;
            margin-bottom: 3.5mm;
        }

        .local-data { font-size: 11pt; color: #6b7280; margin-bottom: 4mm; }

        /* ── Rodapé ── */
        .ftr       { display: table; width: 100%; }
        .ftr-col   { display: table-cell; vertical-align: middle; }
        .ftr-l     { width: 30%; text-align: left;   padding-left: 4mm; }
        .ftr-c     { width: 40%; text-align: center; }
        .ftr-r     { width: 30%; text-align: right;  padding-right: 4mm; }

        /* XI JORNADA */
        .jornada     { display: inline-block; text-align: center; }
        .jornada img { height: 13mm; width: auto; display: block; margin: 0 auto 1.5mm; }
        .ji          { font-size: 11pt; font-weight: 800; line-height: 1; }
        .ji-xi       { color: #0f4c81; }
        .ji-j        { color: #f37021; }
        .jn          { font-size: 6.5pt; font-weight: 700; color: #0f4c81; letter-spacing: 1.5px; margin-top: 0.5mm; }

        /* Assinatura */
        .assin      { display: inline-block; text-align: center; min-width: 60mm; }
        .assin-role { font-size: 8pt; font-weight: 700; color: #0f4c81; letter-spacing: 1px; margin-bottom: 7mm; }
        .assin-line { border-top: 1px solid #374151; width: 54mm; margin: 0 auto 2mm; }
        .assin-name { font-size: 9.5pt; font-weight: 700; color: #111827; }

        /* QR */
        .qr-wrap { display: inline-block; text-align: center; }
        .qr-wrap img   { width: 16mm; height: 16mm; display: block; margin: 0 auto 1mm; }
        .qr-wrap small { font-size: 5.5pt; color: #9ca3af; font-family: monospace; display: block; }
    </style>
</head>
<body>
<div class="page">

    @php
        $logoPath    = public_path('storage/branding/logo.png');
        $simboloPath = public_path('storage/branding/simbolo.png');
    @endphp

    {{-- Marca de água --}}
    @if (file_exists($logoPath))
        <img src="{{ $logoPath }}" class="watermark" alt="">
    @endif

    {{-- Triângulos --}}
    <span class="tri tl-b"></span>
    <span class="tri tl-o"></span>
    <span class="tri ml-b"></span>
    <span class="tri ml-o"></span>
    <span class="tri bl-b1"></span>
    <span class="tri bl-s"></span>
    <span class="tri bl-o"></span>
    <span class="tri bl-b2"></span>
    <span class="tri tr-o"></span>
    <span class="tri tr-b"></span>
    <span class="tri tr-s"></span>
    <span class="tri br-o"></span>

    <div class="content">

        {{-- Cabeçalho --}}
        <div class="hdr-table">
            <div class="hdr-logo">
                @if (file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="ISPM">
                @endif
            </div>
            <div class="hdr-sep"><span class="hdr-sep-line"></span></div>
            <div class="hdr-txt">
                <div class="h-ispm">ISPM</div>
                <div class="h-inst">Instituto Superior<br>Politécnico Maravilha</div>
                <div class="h-dec">Criado pelo Decreto Presidencial n.º 168/12, de 24 de Julho</div>
            </div>
        </div>

        <h1 class="ctitle">CERTIFICADO</h1>

        <p class="certifica">Certifica-se que o (a) &nbsp;Sr. (a) :</p>

        <div class="nome">{{ $certificado->nome }}</div>

        <div class="corpo">{{ $certificado->corpo_texto }}</div>

        <div class="local-data">
            Benguela, {{ $certificado->data_evento->translatedFormat('d \d\e F \d\e Y') }}
        </div>

        {{-- Rodapé --}}
        <table class="ftr" width="100%">
            <tr>
                <td class="ftr-col ftr-l">
                    <div class="qr-wrap">
                        @if ($qr = $certificado->qr_data_uri)
                            <img src="{{ $qr }}" alt="QR">
                        @endif
                        <small>{{ $certificado->codigo }}</small>
                    </div>
                </td>
                <td class="ftr-col ftr-c">
                    <div class="jornada">
                        @if (file_exists($simboloPath))
                            <img src="{{ $simboloPath }}" alt="XI Jornada">
                        @endif
                        <div class="ji">
                            <span class="ji-xi">xi</span><span class="ji-j">JORNADA</span>
                        </div>
                        <div class="jn">CIENTÍFICO-METODOLÓGICA</div>
                    </div>
                </td>
                <td class="ftr-col ftr-r">
                    <div class="assin">
                        <div class="assin-role">O PRESIDENTE</div>
                        <div class="assin-line"></div>
                        <div class="assin-name">JOSÉ JANUÁRIO, PhD.</div>
                    </div>
                </td>
            </tr>
        </table>

    </div>
</div>
</body>
</html>