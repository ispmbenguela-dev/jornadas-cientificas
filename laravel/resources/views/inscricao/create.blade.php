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

                    <form method="POST" action="{{ route('inscricao.store') }}" enctype="multipart/form-data" class="row g-3">
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
                            <input type="text" name="instituicao" value="{{ old('instituicao') }}" class="form-control" maxlength="160" placeholder="ex.: ISPM" />
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

                        <div class="col-12">
                            <div class="price-preview" id="inscPriceBox">
                                <i class="bi bi-cash-coin"></i>
                                <span>Selecione categoria e modalidade para ver o valor.</span>
                                <strong id="inscPriceValue">—</strong>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Comprovativo de pagamento (PDF/JPG/PNG · até 5 MB)</label>
                            <input type="file" name="comprovativo" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
                            <small class="text-muted">Opcional agora — pode enviar depois por e-mail para vp.cientifica@ispmaravilha.com.</small>
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
    const precos = @json($precos);
    const cat = document.getElementById('inscCategoria');
    const mod = document.getElementById('inscModalidade');
    const out = document.getElementById('inscPriceValue');
    const box = document.getElementById('inscPriceBox');
    const fmt = (n) => new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2 }).format(n) + ' Kz';
    function refresh() {
        const c = cat.value, m = mod.value;
        if (c && m && precos[c] && precos[c][m] !== undefined) {
            out.textContent = fmt(precos[c][m]);
            box.classList.add('is-set');
        } else {
            out.textContent = '—';
            box.classList.remove('is-set');
        }
    }
    cat.addEventListener('change', refresh);
    mod.addEventListener('change', refresh);
    refresh();
</script>
@endpush
@endsection
