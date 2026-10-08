@props([
    'id' => null,
    'title' => 'Reference drawing: the enforcement points around an environment, from mail edge and identity through workloads to stored data, with continuous monitoring beneath.',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);

    // Laid out two by two rather than four in a row. The narrow single row forced
    // 126-unit plates and 10px labels; the grid gives each enforcement point 300
    // units, so the labels sit at normal reading size.
    $points = [
        ['Mail edge', ['SPF · DKIM · DMARC', 'INBOUND AND OUTBOUND FILTERING'], 0, 0],
        ['Identity', ['ENTRA ID · MFA', 'ZERO TRUST USER ACCESS'], 340, 0],
        ['Workloads', ['CROWDSTRIKE · SOPHOS · DEFENDER', 'IDS/IPS MONITORING'], 0, 116],
        ['Data', ['IMMUTABLE VERSIONING', 'RBAC · AUDIT LOGGING'], 340, 116],
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 640 304" role="img"
     data-label-budget="32"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    @foreach ($points as [$name, $lines, $x, $y])
        <rect class="plate" x="{{ $x }}" y="{{ $y }}" width="300" height="96" />
        <path class="signal" d="M{{ $x + 24 }} {{ $y + 20 }} h18" />
        <text class="code" x="{{ $x + 52 }}" y="{{ $y + 24 }}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</text>
        <text class="name" x="{{ $x + 24 }}" y="{{ $y + 52 }}">{{ $name }}</text>
        @foreach ($lines as $index => $line)
            <text class="value" x="{{ $x + 24 }}" y="{{ $y + 74 + ($index * 20) }}">{{ $line }}</text>
        @endforeach
    @endforeach

    {{-- One monitoring line under every point --}}
    <circle class="dot" cx="16" cy="272" r="4" />
    {{-- A full-width rail caption, not an in-plate value. --}}
    <text class="foot" x="34" y="276">CONTINUOUS MONITORING · PATCHING · REMEDIATION</text>
    <text class="code" x="640" y="276" text-anchor="end">PERIMETER</text>
</svg>
