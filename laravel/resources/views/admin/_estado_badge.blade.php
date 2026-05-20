@php
    $map = [
        'pendente'    => ['warning',  'bi-hourglass-split', 'Pendente'],
        'confirmada'  => ['success',  'bi-check-circle',    'Confirmada'],
        'rejeitada'   => ['danger',   'bi-x-circle',        'Rejeitada'],
        'admitida'    => ['success',  'bi-check2-square',   'Admitida'],
    ];
    [$cls, $icon, $label] = $map[$estado] ?? ['secondary', 'bi-circle', ucfirst($estado)];
@endphp
<span class="badge bg-{{ $cls }}"><i class="bi {{ $icon }}"></i> {{ $label }}</span>
