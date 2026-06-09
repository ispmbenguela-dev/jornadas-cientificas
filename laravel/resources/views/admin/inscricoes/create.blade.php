@extends('layouts.admin')

@section('title', 'Nova Inscrição')
@section('page_title', 'Nova Inscrição')

@section('topbar_actions')
    <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.inscricoes.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            {{-- Coluna principal --}}
            <div class="col-lg-8">

                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-person"></i> Dados pessoais</h4>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nome completo *</label>
                            <input type="text" name="nome" value="{{ old('nome') }}"
                                   class="form-control" required maxlength="160">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control" required maxlength="160">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone *</label>
                            <input type="text" name="telefone" value="{{ old('telefone') }}"
                                   class="form-control" required maxlength="40">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Instituição</label>
                            <input type="text" name="instituicao" value="{{ old('instituicao') }}"
                                   class="form-control" maxlength="160">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input type="hidden" name="is_docente_ispm" value="0">
                                <input type="checkbox" name="is_docente_ispm" value="1"
                                       id="is_docente_ispm" class="form-check-input"
                                       @checked(old('is_docente_ispm'))>
                                <label class="form-check-label" for="is_docente_ispm">Docente ISPM verificado</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail institucional</label>
                            <input type="email" name="email_institucional"
                                   value="{{ old('email_institucional') }}"
                                   class="form-control" maxlength="160">
                        </div>
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-card-checklist"></i> Inscrição</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Categoria *</label>
                            <select name="categoria" id="adm_cat" class="form-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach ($categorias as $k => $label)
                                    <option value="{{ $k }}" @selected(old('categoria') === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Modalidade *</label>
                            <select name="modalidade" id="adm_mod" class="form-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach ($modalidades as $k => $label)
                                    <option value="{{ $k }}" @selected(old('modalidade') === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (!empty($miniCursos))
                        <div class="col-12 d-none" id="adm_mc_wrap">
                            <label class="form-label">Mini-Cursos</label>
                            <div class="row g-2">
                                @foreach ($miniCursos as $chave => $mc)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" name="mini_cursos[]"
                                                   value="{{ $chave }}" id="mc_{{ $chave }}"
                                                   class="form-check-input adm-mc-check"
                                                   @checked(in_array($chave, old('mini_cursos', [])))>
                                            <label class="form-check-label small" for="mc_{{ $chave }}">
                                                <strong>{{ $mc['hora'] }}</strong> · {{ $mc['local'] }}<br>
                                                <span class="text-muted">{{ $mc['titulo'] }}</span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-cash-coin"></i> Pagamento</h4>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Valor (Kz)</label>
                            <input type="number" name="valor_kz" id="adm_valor_kz"
                                   value="{{ old('valor_kz') }}"
                                   class="form-control" min="0"
                                   placeholder="Auto-calculado">
                            <small class="text-muted">Deixe em branco para calcular automaticamente.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Valor declarado (Kz)</label>
                            <input type="number" name="valor_pago_informado"
                                   value="{{ old('valor_pago_informado') }}"
                                   class="form-control" min="0" step="1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Referência</label>
                            <input type="text" name="referencia_pagamento"
                                   value="{{ old('referencia_pagamento') }}"
                                   class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Validação do pagamento</label>
                            <select name="validacao_pagamento" class="form-select">
                                @foreach ($validacoes as $k => $label)
                                    <option value="{{ $k }}" @selected(old('validacao_pagamento', 'pendente') === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Comprovativo (PDF/JPG/PNG · até 5 MB)</label>
                            <input type="file" name="comprovativo" class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

                <div class="panel mt-3" id="adm_cracha_panel" style="display:none">
                    <h4 class="mb-3"><i class="bi bi-person-badge"></i> Crachá (PTA)</h4>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Crachá de funcionário (PDF/JPG/PNG · até 5 MB)</label>
                            <input type="file" name="cracha" id="adm_cracha"
                                   class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

            </div>

            {{-- Coluna lateral --}}
            <div class="col-lg-4">
                <div class="panel">
                    <h4 class="mb-3"><i class="bi bi-calculator"></i> Valor estimado</h4>
                    <div class="p-3 rounded text-center" style="background:var(--surface-alt,#f8f9fa)">
                        <span class="text-muted small d-block mb-1">Baseado na categoria e modalidade</span>
                        <strong id="adm_price_preview" style="font-size:1.4rem">—</strong>
                    </div>
                    <small class="text-muted d-block mt-2">
                        Pode substituir o valor no campo "Valor (Kz)" à esquerda.
                    </small>
                </div>

                <div class="panel mt-3">
                    <h4 class="mb-3"><i class="bi bi-flag"></i> Estado</h4>
                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select name="estado" class="form-select" required>
                            <option value="pendente"   @selected(old('estado', 'pendente') === 'pendente')>Pendente</option>
                            <option value="confirmada" @selected(old('estado') === 'confirmada')>Confirmada</option>
                            <option value="rejeitada"  @selected(old('estado') === 'rejeitada')>Rejeitada</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Observações</label>
                        <textarea name="observacoes" rows="4" class="form-control">{{ old('observacoes') }}</textarea>
                    </div>
                </div>

                @if ($edicao)
                    <div class="panel mt-3">
                        <p class="mb-0 small text-muted">
                            <i class="bi bi-info-circle"></i>
                            Será associada à edição <strong>{{ $edicao->nome_curto }}</strong>.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-cta"><i class="bi bi-person-plus"></i> Criar inscrição</button>
            <a href="{{ route('admin.inscricoes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const admPrecos  = @json($precos);
    const admCat     = document.getElementById('adm_cat');
    const admMod     = document.getElementById('adm_mod');
    const admMcWrap  = document.getElementById('adm_mc_wrap');
    const admPrice   = document.getElementById('adm_price_preview');
    const admValorKz = document.getElementById('adm_valor_kz');
    const admCrachaPanel = document.getElementById('adm_cracha_panel');
    const admCracha  = document.getElementById('adm_cracha');

    const fmt = n => new Intl.NumberFormat('pt-PT').format(n) + ' Kz';

    function admMcCount() {
        return document.querySelectorAll('.adm-mc-check:checked').length;
    }

    function admRefresh() {
        const c = admCat.value, m = admMod.value;

        // Mini-cursos: mostrar apenas quando modalidade = mini_curso
        if (admMcWrap) {
            admMcWrap.classList.toggle('d-none', m !== 'mini_curso');
            if (m !== 'mini_curso') {
                document.querySelectorAll('.adm-mc-check').forEach(el => el.checked = false);
            }
        }

        // Crachá: mostrar apenas para PTA
        if (admCrachaPanel) {
            admCrachaPanel.style.display = c === 'pta' ? '' : 'none';
            if (admCracha) admCracha.required = c === 'pta';
        }

        // Preço estimado
        if (!c || !m || !admPrecos[c] || admPrecos[c][m] === undefined) {
            admPrice.textContent = '—';
            return;
        }

        let val = admPrecos[c][m];
        if (m === 'mini_curso') {
            val = val * Math.max(1, admMcCount());
        }
        admPrice.textContent = val > 0 ? fmt(val) : 'Gratuito';

        // Preenche valor_kz automaticamente se ainda estiver vazio
        if (!admValorKz.value) {
            admValorKz.placeholder = val > 0 ? fmt(val) : '0 (gratuito)';
        }
    }

    admCat.addEventListener('change', admRefresh);
    admMod.addEventListener('change', admRefresh);
    document.addEventListener('change', e => {
        if (e.target.classList.contains('adm-mc-check')) admRefresh();
    });

    admRefresh();
</script>
@endpush