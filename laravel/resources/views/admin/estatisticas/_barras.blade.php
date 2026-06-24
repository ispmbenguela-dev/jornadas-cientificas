@php
    $totalRef = $totalRef ?: max(1, array_sum($dados));
    $i = 0;
@endphp
<div class="d-flex flex-column gap-3">
    @foreach ($dados as $label => $n)
        @php
            $pct = $totalRef > 0 ? round($n / $totalRef * 100, 1) : 0;
            $c = $cores[$i % count($cores)];
            $i++;
        @endphp
        <div>
            <div class="d-flex justify-content-between align-items-baseline mb-1">
                <span class="fw-semibold">{{ $label }}</span>
                <span class="text-muted small">{{ number_format($n, 0, ',', '.') }} · {{ $pct }}%</span>
            </div>
            <div class="progress" style="height:10px;background:#eef0f5">
                <div class="progress-bar" role="progressbar"
                     style="width:{{ $pct }}%;background:{{ $c }}"
                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    @endforeach
</div>