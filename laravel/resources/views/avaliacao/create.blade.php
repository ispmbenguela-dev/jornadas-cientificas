@extends('layouts.app')

@section('title', 'Avaliação da Jornada — ISPM')

@section('content')
<section class="section section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Formulário de avaliação</span>
            <h2 class="section-title">Avalie as <span class="text-accent">Jornadas Científicas</span></h2>
            <p class="section-lead mx-auto" style="max-width: 720px">
                O presente questionário tem como objetivo avaliar o grau de satisfação dos participantes
                das Jornadas Científicas do ISPM. As suas respostas são
                <strong>confidenciais</strong> e serão utilizadas apenas para fins de avaliação e
                melhoria das futuras edições do evento.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-9">
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

                    <form method="POST" action="{{ route('avaliacao.store') }}" class="row g-3" id="avalForm" novalidate>
                        @csrf

                        {{-- Dados Gerais --}}
                        <div class="col-12">
                            <h5 class="mb-3 pb-2 border-bottom">
                                <i class="bi bi-person-circle text-accent"></i> Dados Gerais
                            </h5>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Sexo *</label>
                            <div class="d-flex gap-4 mt-1">
                                <div class="form-check">
                                    <input type="radio" name="sexo" value="M" id="sexo_m"
                                           class="form-check-input" @checked(old('sexo') === 'M') required>
                                    <label class="form-check-label" for="sexo_m">Masculino</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="sexo" value="F" id="sexo_f"
                                           class="form-check-input" @checked(old('sexo') === 'F')>
                                    <label class="form-check-label" for="sexo_f">Feminino</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="idade">Idade *</label>
                            <input type="number" name="idade" id="idade" value="{{ old('idade') }}"
                                   class="form-control" min="15" max="100" required placeholder="Ex: 24">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="categoria">Categoria *</label>
                            <select name="categoria" id="categoria" class="form-select" required>
                                <option value="">— Seleccione —</option>
                                @foreach (\App\Models\Avaliacao::CATEGORIAS as $k => $label)
                                    <option value="{{ $k }}" @selected(old('categoria') === $k)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Secções Likert --}}
                        @php
                            $qNum = 0;
                            $secoes = \App\Models\Avaliacao::SECOES;
                            $likert = \App\Models\Avaliacao::LIKERT;
                        @endphp

                        @foreach ($secoes as $secaoKey => $secao)
                        <div class="col-12 mt-3">
                            <h5 class="mb-3 pb-2 border-bottom">
                                <i class="bi {{ $secao['icone'] }} text-accent"></i>
                                {{ $secao['titulo'] }}
                            </h5>
                        </div>

                        @foreach ($secao['perguntas'] as $qKey => $pergunta)
                        @php $qNum++ @endphp
                        <div class="col-12">
                            <p class="mb-2 fw-medium">
                                <span class="badge bg-secondary me-2">{{ $qNum }}</span>{{ $pergunta }}
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($likert as $val => $label)
                                <div class="form-check form-check-inline avl-option"
                                     title="{{ $label }}">
                                    <input type="radio" name="{{ $qKey }}" value="{{ $val }}"
                                           id="{{ $qKey }}_{{ $val }}"
                                           class="form-check-input avl-radio"
                                           @checked(old($qKey) == $val) required>
                                    <label class="form-check-label avl-label" for="{{ $qKey }}_{{ $val }}">
                                        <span class="avl-num">{{ $val }}</span>
                                        <small class="avl-text d-none d-md-block">{{ $label }}</small>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                        @endforeach

                        {{-- Perguntas Abertas --}}
                        <div class="col-12 mt-3">
                            <h5 class="mb-3 pb-2 border-bottom">
                                <i class="bi bi-chat-left-text text-accent"></i> Perguntas Abertas
                            </h5>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="aspetos_positivos">
                                Quais foram os aspetos mais positivos das jornadas científicas?
                            </label>
                            <textarea name="aspetos_positivos" id="aspetos_positivos"
                                      rows="4" class="form-control" maxlength="2000"
                                      placeholder="Partilhe a sua opinião...">{{ old('aspetos_positivos') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="sugestoes">
                                Que sugestões apresenta para melhorar as próximas edições?
                            </label>
                            <textarea name="sugestoes" id="sugestoes"
                                      rows="4" class="form-control" maxlength="2000"
                                      placeholder="As suas sugestões são muito importantes para nós...">{{ old('sugestoes') }}</textarea>
                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-cta btn-lg">
                                <i class="bi bi-send-check"></i> Submeter avaliação
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Legenda lateral --}}
            <div class="col-lg-3 d-none d-lg-block">
                <div class="form-card" id="avl-legend" style="position:sticky; top:80px">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle text-accent"></i> Escala de avaliação</h6>
                    @foreach (\App\Models\Avaliacao::LIKERT as $val => $label)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge avl-badge-{{ $val }} fs-6" style="min-width:2rem">{{ $val }}</span>
                        <small>{{ $label }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    // Ajuste sticky da legenda
    const legend = document.getElementById('avl-legend');
    const nav    = document.getElementById('mainNav');
    if (legend && nav) {
        function ajustarTop() { legend.style.top = (nav.offsetHeight + 16) + 'px'; }
        ajustarTop();
        window.addEventListener('resize', ajustarTop);
        window.addEventListener('scroll', ajustarTop, { passive: true });
    }

    // Validação client-side das perguntas Likert
    const form = document.getElementById('avalForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        let primeiroErro = null;

        // Verificar as 20 perguntas (q1 a q20)
        for (let i = 1; i <= 20; i++) {
            const name  = 'q' + i;
            const grupo = form.querySelectorAll('input[name="' + name + '"]');
            const respondido = Array.from(grupo).some(r => r.checked);

            if (!respondido) {
                // Destacar o bloco da pergunta
                if (grupo.length > 0) {
                    const bloco = grupo[0].closest('.col-12');
                    if (bloco) {
                        bloco.classList.add('avl-missing');
                        if (!primeiroErro) primeiroErro = bloco;
                    }
                }
            } else {
                // Limpar destaque se já respondido
                const bloco = grupo[0]?.closest('.col-12');
                if (bloco) bloco.classList.remove('avl-missing');
            }
        }

        if (primeiroErro) {
            e.preventDefault();
            primeiroErro.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Mostrar aviso no topo
            let alerta = document.getElementById('avl-alerta-js');
            if (!alerta) {
                alerta = document.createElement('div');
                alerta.id = 'avl-alerta-js';
                alerta.className = 'alert alert-warning mb-3';
                alerta.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Por favor responda a <strong>todas as perguntas</strong> antes de submeter. As perguntas em falta estão assinaladas a vermelho.';
                form.insertBefore(alerta, form.firstChild);
            }
        }
    });

    // Limpar destaque ao seleccionar uma opção
    form.addEventListener('change', function (e) {
        if (e.target.matches('input[type="radio"]')) {
            const bloco = e.target.closest('.col-12');
            if (bloco) bloco.classList.remove('avl-missing');

            const alerta = document.getElementById('avl-alerta-js');
            if (alerta) {
                // Verificar se ainda há perguntas em falta
                const ainda = document.querySelector('.avl-missing');
                if (!ainda) alerta.remove();
            }
        }
    });
})();
</script>
@endpush

@push('head')
<style>
    .avl-option { margin: 0; }
    .avl-option .avl-label {
        display: flex; flex-direction: column; align-items: center;
        cursor: pointer; padding: .5rem .9rem;
        border: 2px solid #dee2e6; border-radius: .5rem;
        transition: all .15s ease; background: #fff; min-width: 80px;
    }
    .avl-option .form-check-input { display: none; }
    .avl-option .form-check-input:checked + .avl-label { border-color: var(--color-primary, #0f4c81); background: var(--color-primary, #0f4c81); color: #fff; }
    .avl-option:has([value="1"]) .form-check-input:checked + .avl-label  { border-color:#dc3545; background:#dc3545; }
    .avl-option:has([value="2"]) .form-check-input:checked + .avl-label  { border-color:#fd7e14; background:#fd7e14; }
    .avl-option:has([value="3"]) .form-check-input:checked + .avl-label  { border-color:#ffc107; background:#ffc107; color:#212529; }
    .avl-option:has([value="4"]) .form-check-input:checked + .avl-label  { border-color:#198754; background:#198754; }
    .avl-option:has([value="5"]) .form-check-input:checked + .avl-label  { border-color:#0f4c81; background:#0f4c81; }
    .avl-num { font-size: 1.25rem; font-weight: 700; line-height: 1; }
    .avl-text { font-size: .65rem; text-align: center; margin-top: .2rem; }
    .avl-badge-1 { background:#dc3545 !important; }
    .avl-badge-2 { background:#fd7e14 !important; }
    .avl-badge-3 { background:#ffc107 !important; color:#212529 !important; }
    .avl-badge-4 { background:#198754 !important; }
    .avl-badge-5 { background:#0f4c81 !important; }
    .avl-missing { background: #fff5f5; border-radius: .5rem; padding: .5rem .75rem; outline: 2px solid #dc3545; }
    .avl-missing .badge { background: #6c757d !important; }
    .avl-missing > p { color: #dc3545 !important; }
</style>
@endpush