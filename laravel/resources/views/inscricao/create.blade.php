@extends('layouts.app')

@section('title', 'Inscrição — XI Jornada ISPM')

@section('content')
<section class="section section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Inscrição online</span>
            <h2 class="section-title">Faça a sua <span class="text-accent">inscrição</span></h2>
            <p class="section-lead mx-auto" style="max-width: 720px">
                Preencha os seus dados, escolha a categoria e modalidade, e envie
                o comprovativo de pagamento. A confirmação será enviada por
                e-mail pela Comissão Científica.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-8">
                <div class="form-card">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong><i class="bi bi-exclamation-triangle-fill"></i> Verifique os campos:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('inscricao.store') }}" enctype="multipart/form-data" class="row g-3" id="inscForm">
                        @csrf

                        <div class="col-md-7">
                            <label class="form-label">Nome completo *</label>
                            <input type="text" name="nome" value="{{ old('nome') }}" class="form-control" required maxlength="160" />
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Telefone *</label>
                            <input type="tel" name="telefone" value="{{ old('telefone') }}" class="form-control" required maxlength="40" placeholder="ex.: 9XX XXX XXX" />
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" required maxlength="160" />
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Instituição</label>
                            <input type="text" name="instituicao" id="inscInstituicao" value="{{ old('instituicao') }}" class="form-control" maxlength="160" placeholder="ex.: ISPM" />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Categoria *</label>
                            <select name="categoria" id="inscCategoria" class="form-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach ($categorias as $key => $label)
                                    <option value="{{ $key }}" @selected(old('categoria') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Modalidade *</label>
                            <select name="modalidade" id="inscModalidade" class="form-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach ($modalidades as $key => $label)
                                    <option value="{{ $key }}" @selected(old('modalidade') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nota de validação para membros da Comissão Organizadora --}}
                        <div class="col-12 d-none" id="inscMcoWrapper">
                            <div class="sigam-card">
                                <div class="sigam-head">
                                    <i class="bi bi-people-fill"></i>
                                    <div>
                                        <strong>Comissão Organizadora — participação gratuita</strong>
                                        <small class="d-block text-muted">
                                            A participação dos membros da Comissão Organizadora é gratuita e não requer comprovativo de pagamento.
                                            O seu <strong>nome</strong> e <strong>e-mail</strong> serão validados contra a lista registada pela organização.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Verificação docente ISPM via SIGAM --}}
                        <div class="col-12 d-none" id="inscSigamWrapper">
                            <div class="sigam-card">
                                <div class="sigam-head">
                                    <i class="bi bi-shield-check"></i>
                                    <div>
                                        <strong>Verificação de Docente do ISPM</strong>
                                        <small class="d-block text-muted">
                                            Docentes do ISPM têm direito a <strong>um mini-curso gratuito</strong>.
                                            Informe o e-mail institucional para confirmar.
                                        </small>
                                    </div>
                                </div>
                                <div class="row g-2 mt-2">
                                    <div class="col-md-8">
                                        <input type="email"
                                               name="email_institucional"
                                               id="inscEmailInst"
                                               value="{{ old('email_institucional') }}"
                                               class="form-control"
                                               maxlength="160"
                                               placeholder="e.g. nome.apelido@ispm.ao" />
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-cta w-100" id="btnVerifySigam">
                                            <i class="bi bi-search"></i> Verificar
                                        </button>
                                    </div>
                                </div>
                                <div id="sigamResult" class="sigam-result d-none mt-3"></div>
                            </div>
                        </div>

                        <div class="col-12 d-none" id="inscMiniCursoWrapper">
                            <label class="form-label d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <span id="mcLabelText">Escolha os mini-cursos pretendidos *</span>
                                <small class="text-muted" id="mcHint">
                                    <i class="bi bi-info-circle"></i>
                                    Pode escolher mais do que um — o valor é multiplicado.
                                </small>
                            </label>

                            @php($selecionados = (array) old('mini_cursos', []))
                            @php($miniCursosPorDia = collect($miniCursos)->groupBy('dia', preserveKeys: true))

                            <div class="mc-list">
                                @foreach ($miniCursosPorDia as $dia => $items)
                                    <div class="mc-day">
                                        <h6 class="mc-day-title"><i class="bi bi-calendar2-day"></i> {{ $dia }}</h6>
                                        <div class="row g-3">
                                            @foreach ($items as $key => $mc)
                                                <div class="col-md-6">
                                                    <label class="mc-option">
                                                        <input type="checkbox"
                                                               name="mini_cursos[]"
                                                               value="{{ $key }}"
                                                               class="mc-option-check"
                                                               @checked(in_array($key, $selecionados, true))>
                                                        <span class="mc-option-content">
                                                            <span class="mc-option-head">
                                                                <span class="hbui-badge hbui-badge-default">{{ $mc['hora'] }}</span>
                                                                <span class="hbui-badge hbui-badge-room">{{ $mc['local'] }}</span>
                                                                <span class="hbui-badge hbui-badge-outline-soft">{{ $mc['tema'] }}</span>
                                                            </span>
                                                            <span class="mc-option-title">{{ $mc['titulo'] }}</span>
                                                            <span class="mc-option-meta">
                                                                <span><i class="bi bi-mic-fill"></i> {{ $mc['prelector'] }}</span>
                                                                <span><i class="bi bi-person-fill"></i> Mod.: {{ $mc['moderador'] }}</span>
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mc-summary mt-2">
                                <i class="bi bi-check2-square"></i>
                                <span><strong id="mcCount">0</strong> mini-curso(s) seleccionado(s).</span>
                                <span class="mc-summary-extra d-none" id="mcFreeNote">
                                    · <strong class="text-success"><i class="bi bi-gift"></i> 1.º é gratuito</strong>
                                </span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="price-preview" id="inscPriceBox">
                                <i class="bi bi-cash-coin"></i>
                                <span id="inscPriceLabel">Selecione categoria e modalidade para ver o valor.</span>
                                <strong id="inscPriceValue">—</strong>
                            </div>
                        </div>

                        {{-- Crachá: obrigatório para Pessoal Técnico Administrativo --}}
                        <div class="col-12 d-none" id="inscCrachaWrapper">
                            <div class="pay-card">
                                <div class="pay-head">
                                    <i class="bi bi-person-badge"></i>
                                    <strong>Passe de funcionário</strong>
                                </div>
                                <p class="text-muted small mb-2">
                                    Para a categoria <strong>Pessoal Técnico Administrativo</strong> é obrigatório carregar o passe de funcionário do ISPM.
                                </p>
                                <label class="form-label">Passe de funcionário (PDF/JPG/PNG · até 5 MB) *</label>
                                <input type="file" name="cracha" id="inscCracha"
                                       class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
                            </div>
                        </div>

                        {{-- Campos de comprovativo: aparecem só quando há valor a pagar --}}
                        <div class="col-12 d-none" id="inscPayWrapper">
                            <div class="pay-card">
                                <div class="pay-head">
                                    <i class="bi bi-receipt"></i>
                                    <strong>Comprovativo de pagamento</strong>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-5">
                                        <label class="form-label">Valor pago (Kz) *</label>
                                        <input type="number" name="valor_pago_informado" id="inscValorPago"
                                               value="{{ old('valor_pago_informado') }}"
                                               class="form-control" min="0" step="1"
                                               placeholder="ex.: 5000" />
                                        <small class="text-muted">Escreva exactamente o valor depositado.</small>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Referência do depósito *</label>
                                        <input type="text" name="referencia_pagamento" id="inscRefPag"
                                               value="{{ old('referencia_pagamento') }}"
                                               class="form-control" maxlength="80"
                                               placeholder="ex.: ABC123456789 (talão/IBAN)" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Ficheiro do comprovativo (PDF/JPG/PNG · até 5 MB) *</label>
                                        <input type="file" name="comprovativo" id="inscComprovativo"
                                               class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
                                    </div>
                                </div>

                                <div id="payCheck" class="pay-check d-none mt-3"></div>
                            </div>
                        </div>

                        <div class="col-12 d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-cta">
                                <i class="bi bi-check-circle"></i> Submeter inscrição
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-ghost">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bank-card">
                    <span class="hbui-badge hbui-badge-outline-soft mb-3">
                        <i class="bi bi-bank"></i> Dados bancários
                    </span>
                    <h4 class="bank-title">Onde pagar</h4>
                    <ul class="bank-list">
                        <li><span class="bank-key">Banco</span><span class="bank-val">BPC</span></li>
                        <li><span class="bank-key">IBAN</span><span class="bank-val bank-iban">0010.0455.0165.8843.0116.9</span></li>
                        <li><span class="bank-key">Beneficiário</span><span class="bank-val">Instituto Superior Politécnico Maravilha</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const precos          = @json($precos);
    const ispmAliases     = @json($instituicoesIspm);
    const verifyUrl       = @json(route('inscricao.verificar_docente'));
    const csrf            = document.querySelector('meta[name="csrf-token"]').content;

    const cat             = document.getElementById('inscCategoria');
    const mod             = document.getElementById('inscModalidade');
    const inst            = document.getElementById('inscInstituicao');
    const sigamWrap       = document.getElementById('inscSigamWrapper');
    const emailInst       = document.getElementById('inscEmailInst');
    const btnVerify       = document.getElementById('btnVerifySigam');
    const sigamResult     = document.getElementById('sigamResult');

    const out             = document.getElementById('inscPriceValue');
    const box             = document.getElementById('inscPriceBox');
    const priceLabel      = document.getElementById('inscPriceLabel');

    const mcWrap          = document.getElementById('inscMiniCursoWrapper');
    const mcChecks        = () => Array.from(document.querySelectorAll('.mc-option-check'));
    const mcCountEl       = document.getElementById('mcCount');
    const mcFreeNote      = document.getElementById('mcFreeNote');
    const mcHint          = document.getElementById('mcHint');
    const mcLabelText     = document.getElementById('mcLabelText');

    const payWrap         = document.getElementById('inscPayWrapper');
    const valorPagoEl     = document.getElementById('inscValorPago');
    const refPagEl        = document.getElementById('inscRefPag');
    const comprovativoEl  = document.getElementById('inscComprovativo');
    const payCheck        = document.getElementById('payCheck');
    const crachaWrap      = document.getElementById('inscCrachaWrapper');
    const crachaEl        = document.getElementById('inscCracha');
    const mcoWrap         = document.getElementById('inscMcoWrapper');

    let isDocenteIspm = false; // só fica true após verificação SIGAM positiva
    let lastTotal = 0;

    const fmt = (n) => new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2 }).format(n) + ' Kz';
    const fmtNoDec = (n) => new Intl.NumberFormat('pt-PT').format(n) + ' Kz';

    function instituicaoIsIspm() {
        const v = (inst.value || '').trim().toLowerCase();
        return ispmAliases.includes(v);
    }

    function selectedCount() {
        return mcChecks().filter(c => c.checked).length;
    }

    function refreshSigamWrapper() {
        const elegivel = cat.value === 'docente' && instituicaoIsIspm();
        sigamWrap.classList.toggle('d-none', !elegivel);
        if (!elegivel) {
            isDocenteIspm = false;
            sigamResult.classList.add('d-none');
            sigamResult.innerHTML = '';
        }
        refreshFreeNote();
        refreshPrice();
    }

    // single-pick: docente ISPM em modo Participação → escolhe 1 mini-curso gratuito (bónus)
    function isSinglePickBonus() {
        return isDocenteIspm && mod.value === 'participacao';
    }

    function shouldShowMcWrapper() {
        return mod.value === 'mini_curso' || isSinglePickBonus();
    }

    function refreshFreeNote() {
        // nota "1.º gratuito" só faz sentido no modo mini_curso multi-pick
        mcFreeNote.classList.toggle('d-none', !isDocenteIspm || mod.value !== 'mini_curso');
    }

    function refreshMcLabels() {
        if (isSinglePickBonus()) {
            mcLabelText.textContent = 'Escolha 1 mini-curso gratuito *';
            mcHint.innerHTML = '<i class="bi bi-gift"></i> Bónus docente ISPM — apenas 1 mini-curso.';
        } else {
            mcLabelText.textContent = 'Escolha os mini-cursos pretendidos *';
            mcHint.innerHTML = '<i class="bi bi-info-circle"></i> Pode escolher mais do que um — o valor é multiplicado.';
        }
    }

    function refreshPrice() {
        const c = cat.value, m = mod.value;
        if (!c || !m || !precos[c] || precos[c][m] === undefined) {
            out.textContent = '—';
            priceLabel.textContent = 'Selecione categoria e modalidade para ver o valor.';
            box.classList.remove('is-set');
            lastTotal = 0;
            togglePayWrapper();
            return;
        }
        let total = precos[c][m];
        let label = '';
        if (m === 'mini_curso') {
            const n = selectedCount();
            if (n === 0) {
                total = 0;
                label = 'Seleccione pelo menos um mini-curso.';
            } else if (isDocenteIspm) {
                const cobrados = Math.max(0, n - 1);
                total = total * cobrados;
                label = cobrados === 0
                    ? '1 mini-curso · gratuito (docente ISPM).'
                    : `${n} mini-cursos · 1 gratuito + ${cobrados} a pagar.`;
            } else {
                total = total * n;
                label = `${n} mini-curso(s) seleccionado(s).`;
            }
        } else if (m === 'participacao') {
            if (isSinglePickBonus()) {
                total = 0; // docente ISPM = participação + 1 mini-curso totalmente gratuitos
                const n = selectedCount();
                label = n === 0
                    ? 'Escolha 1 mini-curso gratuito (bónus docente ISPM).'
                    : 'Participação + 1 mini-curso · totalmente gratuito (docente ISPM).';
            } else {
                label = 'Taxa de participação.';
            }
        }

        lastTotal = total;
        priceLabel.textContent = label;
        const isFreeDocente = total === 0 && isDocenteIspm && (
            (m === 'mini_curso' && selectedCount() === 1) ||
            (m === 'participacao')
        );
        if (total > 0) {
            out.textContent = fmt(total);
            box.classList.add('is-set');
        } else {
            out.textContent = isFreeDocente ? 'Gratuito' : '—';
            box.classList.remove('is-set');
        }
        togglePayWrapper();
        runPayCheck();
    }

    function toggleCrachaWrapper() {
        const show = cat.value === 'pta';
        crachaWrap.classList.toggle('d-none', !show);
        crachaEl.required = show;
    }

    function toggleMcoWrapper() {
        mcoWrap.classList.toggle('d-none', cat.value !== 'mco');
    }

    function togglePayWrapper() {
        const show = lastTotal > 0;
        payWrap.classList.toggle('d-none', !show);
        valorPagoEl.required = show;
        refPagEl.required = show;
        comprovativoEl.required = show;
        if (!show) {
            payCheck.classList.add('d-none');
            payCheck.innerHTML = '';
        }
    }

    function runPayCheck() {
        if (lastTotal <= 0) return;
        const declarado = parseInt(valorPagoEl.value || '0', 10);
        if (!declarado || isNaN(declarado)) {
            payCheck.classList.add('d-none');
            return;
        }
        payCheck.classList.remove('d-none');
        if (declarado === lastTotal) {
            payCheck.className = 'pay-check pay-check-ok mt-3';
            payCheck.innerHTML = `<i class="bi bi-check2-circle"></i> Valor declarado (<strong>${fmtNoDec(declarado)}</strong>) coincide com o total a pagar.`;
        } else {
            const dif = declarado - lastTotal;
            const sinal = dif > 0 ? 'a mais' : 'a menos';
            payCheck.className = 'pay-check pay-check-warn mt-3';
            payCheck.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Divergência: valor declarado <strong>${fmtNoDec(declarado)}</strong> ≠ total calculado <strong>${fmtNoDec(lastTotal)}</strong> (${fmtNoDec(Math.abs(dif))} ${sinal}).`;
        }
    }

    function refreshCount() {
        const n = selectedCount();
        mcCountEl.textContent = n;
        mcChecks().forEach(c => {
            c.closest('.mc-option').classList.toggle('is-checked', c.checked);
        });
    }

    // Em single-pick, só permite 1 selecção (desmarca outras)
    function enforceSinglePick(triggered) {
        if (!isSinglePickBonus()) return;
        mcChecks().forEach(c => {
            if (c !== triggered && c.checked) c.checked = false;
        });
    }

    function toggleMiniCurso() {
        const show = shouldShowMcWrapper();
        mcWrap.classList.toggle('d-none', !show);
        if (!show) {
            mcChecks().forEach(c => { c.checked = false; });
            refreshCount();
        } else if (isSinglePickBonus()) {
            // garante no máximo 1 ao entrar no modo
            const checked = mcChecks().filter(c => c.checked);
            checked.slice(1).forEach(c => { c.checked = false; });
            refreshCount();
        }
        refreshMcLabels();
        refreshFreeNote();
    }

    async function verifySigam() {
        const email = (emailInst.value || '').trim();
        if (!email) {
            renderSigamMessage('warn', 'Informe o e-mail institucional antes de verificar.');
            return;
        }
        btnVerify.disabled = true;
        renderSigamMessage('loading', 'A consultar SIGAM…');

        try {
            const res = await fetch(verifyUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: JSON.stringify({ email }),
            });
            const json = await res.json();
            if (!res.ok || !json.ok) {
                isDocenteIspm = false;
                renderSigamMessage('warn', json.message || 'Não foi possível verificar.');
            } else if (json.beneficio_usado) {
                isDocenteIspm = false;
                const u = json.user || {};
                renderSigamMessage('warn',
                    `Docente confirmado: <strong>${u.name || '—'}</strong> (${u.email || email}), mas o benefício gratuito já foi usado numa inscrição anterior. Esta inscrição será cobrada como participante normal.`);
            } else if (json.is_docente) {
                isDocenteIspm = true;
                const u = json.user || {};
                renderSigamMessage('ok',
                    `Docente verificado: <strong>${u.name || '—'}</strong> (${u.email || email}). Tem direito a 1 mini-curso gratuito.`);
            } else {
                isDocenteIspm = false;
                renderSigamMessage('warn', json.message || 'Utilizador encontrado, mas não é docente.');
            }
        } catch (e) {
            isDocenteIspm = false;
            renderSigamMessage('warn', 'Falha de rede ao contactar o SIGAM.');
        } finally {
            btnVerify.disabled = false;
            toggleMiniCurso();
            refreshPrice();
        }
    }

    function renderSigamMessage(kind, html) {
        sigamResult.classList.remove('d-none');
        sigamResult.className = 'sigam-result mt-3 sigam-result-' + kind;
        const icon = kind === 'ok' ? 'check-circle-fill'
                   : kind === 'loading' ? 'hourglass-split'
                   : 'exclamation-triangle-fill';
        sigamResult.innerHTML = `<i class="bi bi-${icon}"></i> <span>${html}</span>`;
    }

    cat.addEventListener('change', () => { refreshSigamWrapper(); toggleMiniCurso(); refreshPrice(); toggleCrachaWrapper(); toggleMcoWrapper(); });
    mod.addEventListener('change', () => { toggleMiniCurso(); refreshPrice(); });
    inst.addEventListener('input', () => { refreshSigamWrapper(); toggleMiniCurso(); refreshPrice(); });
    btnVerify.addEventListener('click', verifySigam);
    emailInst.addEventListener('input', () => {
        isDocenteIspm = false;
        sigamResult.classList.add('d-none');
        toggleMiniCurso();
        refreshPrice();
    });
    valorPagoEl.addEventListener('input', runPayCheck);

    document.addEventListener('change', (e) => {
        if (e.target.classList.contains('mc-option-check')) {
            enforceSinglePick(e.target);
            refreshCount();
            refreshPrice();
        }
    });

    refreshCount();
    refreshSigamWrapper();
    toggleMiniCurso();
    refreshPrice();
    toggleCrachaWrapper();
    toggleMcoWrapper();
</script>
@endpush
@endsection
