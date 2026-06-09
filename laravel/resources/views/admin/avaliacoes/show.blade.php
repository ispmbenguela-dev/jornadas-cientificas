@extends('layouts.admin')

@section('title', 'Avaliação #' . $avaliacao->id)
@section('page_title', 'Avaliação #' . $avaliacao->id)

@section('topbar_actions')
    <a href="{{ route('admin.avaliacoes.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
    <form method="POST" action="{{ route('admin.avaliacoes.destroy', $avaliacao) }}"
          onsubmit="return confirm('Remover esta resposta?')" class="d-inline">
        @csrf @method('DELETE')
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Remover</button>
    </form>
@endsection

@section('content')
<div class="row g-3">

    {{-- Dados gerais --}}
    <div class="col-md-4">
        <div class="panel h-100">
            <h5 class="mb-3"><i class="bi bi-person-circle"></i> Dados gerais</h5>
            <dl class="row mb-0">
                <dt class="col-5 text-muted">Sexo</dt>
                <dd class="col-7">{{ $avaliacao->sexo_label }}</dd>
                <dt class="col-5 text-muted">Idade</dt>
                <dd class="col-7">{{ $avaliacao->idade }} anos</dd>
                <dt class="col-5 text-muted">Categoria</dt>
                <dd class="col-7">{{ $avaliacao->categoria_label }}</dd>
                <dt class="col-5 text-muted">Edição</dt>
                <dd class="col-7">{{ $avaliacao->edicao?->nome_curto ?? '—' }}</dd>
                <dt class="col-5 text-muted">Data</dt>
                <dd class="col-7">{{ $avaliacao->created_at->format('d/m/Y H:i') }}</dd>
            </dl>
        </div>
    </div>

    {{-- Média global --}}
    <div class="col-md-4">
        <div class="panel h-100 text-center d-flex flex-column justify-content-center">
            @php $mg = $avaliacao->mediaGlobal(); @endphp
            <div class="fs-1 fw-bold mb-1"
                 style="color: {{ $mg >= 4 ? '#198754' : ($mg >= 3 ? '#fd7e14' : '#dc3545') }}">
                {{ number_format($mg, 2) }}<small class="fs-4 text-muted">/5</small>
            </div>
            <div class="text-muted">Média global</div>
        </div>
    </div>

    {{-- Médias por secção --}}
    <div class="col-md-4">
        <div class="panel h-100">
            <h5 class="mb-3"><i class="bi bi-bar-chart"></i> Por secção</h5>
            @foreach ($secoes as $key => $secao)
            @php $ms = $avaliacao->mediaSecao($key); @endphp
            <div class="d-flex align-items-center gap-2 mb-2">
                <small class="text-muted" style="min-width:120px">{{ $secao['titulo'] }}</small>
                <div class="progress flex-grow-1" style="height:6px">
                    <div class="progress-bar"
                         style="width:{{ ($ms/5)*100 }}%; background: {{ $ms >= 4 ? '#198754' : ($ms >= 3 ? '#fd7e14' : '#dc3545') }}">
                    </div>
                </div>
                <small class="fw-bold" style="min-width:28px">{{ number_format($ms,1) }}</small>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Respostas detalhadas --}}
    @php $qNum = 0; @endphp
    @foreach ($secoes as $secaoKey => $secao)
    <div class="col-12">
        <div class="panel">
            <h5 class="mb-3">
                <i class="bi {{ $secao['icone'] }} text-accent"></i>
                {{ $secao['titulo'] }}
                <span class="badge bg-secondary float-end">
                    Média {{ number_format($avaliacao->mediaSecao($secaoKey), 1) }}/5
                </span>
            </h5>
            @foreach ($secao['perguntas'] as $qKey => $pergunta)
            @php
                $qNum++;
                $val = $avaliacao->$qKey;
                $colors = [1=>'#dc3545',2=>'#fd7e14',3=>'#ffc107',4=>'#198754',5=>'#0f4c81'];
                $cor = $colors[$val] ?? '#6c757d';
            @endphp
            <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                <div class="text-center" style="min-width:50px">
                    <span class="badge fs-5" style="background:{{ $cor }}">{{ $val }}</span>
                    <div><small class="text-muted" style="font-size:.6rem">/5</small></div>
                </div>
                <div>
                    <div class="fw-medium"><span class="text-muted me-1">{{ $qNum }}.</span>{{ $pergunta }}</div>
                    <small style="color:{{ $cor }}">{{ $likert[$val] ?? '' }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Perguntas abertas --}}
    @if ($avaliacao->aspetos_positivos || $avaliacao->sugestoes)
    <div class="col-12">
        <div class="panel">
            <h5 class="mb-3"><i class="bi bi-chat-left-text text-accent"></i> Perguntas abertas</h5>
            @if ($avaliacao->aspetos_positivos)
            <div class="mb-3">
                <div class="fw-medium mb-1">Aspetos positivos:</div>
                <blockquote class="blockquote-sm ps-3 border-start border-3"
                            style="border-color:var(--color-primary,#0f4c81)!important">
                    {{ $avaliacao->aspetos_positivos }}
                </blockquote>
            </div>
            @endif
            @if ($avaliacao->sugestoes)
            <div>
                <div class="fw-medium mb-1">Sugestões:</div>
                <blockquote class="blockquote-sm ps-3 border-start border-3"
                            style="border-color:#198754!important">
                    {{ $avaliacao->sugestoes }}
                </blockquote>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection