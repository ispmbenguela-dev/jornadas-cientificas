@extends('layouts.admin')

@section('title', 'Configurações')
@section('page_title', 'Configurações da marca')

@section('content')
    @php
        $campos = [
            'logo'    => ['key' => 'logo_path',    'titulo' => 'Logo principal',    'icon' => 'bi-image',          'aceita' => '.png,.jpg,.jpeg,.svg,.webp', 'max' => '2 MB', 'hint' => 'Usado na navbar e no rodapé do site público.'],
            'simbolo' => ['key' => 'simbolo_path', 'titulo' => 'Símbolo (favicon)', 'icon' => 'bi-grid-3x3-gap',   'aceita' => '.png,.jpg,.jpeg,.webp',      'max' => '1 MB', 'hint' => 'Mosaico de ícones. Usado como favicon do separador.'],
            'banner'  => ['key' => 'banner_path',  'titulo' => 'Banner principal',  'icon' => 'bi-card-image',     'aceita' => '.png,.jpg,.jpeg,.webp',      'max' => '5 MB', 'hint' => 'Apresentado no topo (hero) da página inicial.'],
        ];
    @endphp

    <form method="POST" action="{{ route('admin.configuracoes.update') }}" enctype="multipart/form-data">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Verifique os campos:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">
            @foreach ($campos as $field => $meta)
                @php
                    $current = $configs[$meta['key']] ?? null;
                    $url     = $current && $current->valor ? asset('storage/' . $current->valor) : null;
                @endphp
                <div class="col-lg-4">
                    <div class="panel branding-card">
                        <h4 class="branding-title"><i class="bi {{ $meta['icon'] }}"></i> {{ $meta['titulo'] }}</h4>
                        <p class="branding-hint">{{ $meta['hint'] }}</p>

                        <div class="branding-preview {{ $field === 'banner' ? 'is-wide' : '' }}">
                            @if ($url)
                                <img src="{{ $url }}" alt="{{ $meta['titulo'] }}" />
                            @else
                                <div class="branding-empty">
                                    <i class="bi bi-image"></i>
                                    <span>Sem imagem definida</span>
                                </div>
                            @endif
                        </div>

                        <div class="mb-2">
                            <label class="form-label small">Nova imagem ({{ $meta['aceita'] }} · até {{ $meta['max'] }})</label>
                            <input type="file" name="{{ $field }}" accept="{{ $meta['aceita'] }}" class="form-control form-control-sm" />
                        </div>

                        @if ($url)
                            <div class="form-check form-check-sm">
                                <input type="checkbox" name="remover_{{ $field }}" id="remover_{{ $field }}" value="1" class="form-check-input" />
                                <label for="remover_{{ $field }}" class="form-check-label small text-danger">
                                    <i class="bi bi-trash"></i> Remover imagem actual
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-cta">
                <i class="bi bi-save"></i> Guardar configurações
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>

    @push('head')
    <style>
        .branding-card { height: 100%; display: flex; flex-direction: column; }
        .branding-title { font-family: var(--ff-display); font-weight: 800; font-size: 1.05rem; color: var(--ink); margin: 0 0 4px; }
        .branding-title i { color: var(--accent); margin-right: 6px; }
        .branding-hint { color: var(--muted); font-size: 0.86rem; margin-bottom: 14px; }
        .branding-preview {
            background: repeating-conic-gradient(#f3f4f6 0% 25%, #fff 0% 50%) 50% / 16px 16px;
            border: 1px dashed var(--line);
            border-radius: 10px;
            min-height: 130px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            margin-bottom: 14px;
        }
        .branding-preview.is-wide { min-height: 90px; }
        .branding-preview img { max-width: 100%; max-height: 200px; object-fit: contain; }
        .branding-empty { display: flex; flex-direction: column; align-items: center; color: var(--muted); gap: 4px; }
        .branding-empty i { font-size: 2rem; }
        .branding-empty span { font-size: 0.85rem; }
    </style>
    @endpush
@endsection
