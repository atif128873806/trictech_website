@props([
    'id' => null,
    'title' => 'Reference drawing: the operating cycle behind the published service level. Continuous monitoring, predictive fault discovery, remediation, then scheduled patching and architecture review, returning to monitoring without a break.',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);

    // Four plates across 920 units with 16-unit gutters. Value lines are set at
    // 12px monospace, so a line must stay inside 186 units — about 25
    // characters. The declared budget below is that limit less one.
    $steps = [
        ['01', 'Monitor', ['24/7 surveillance,', 'performance baselines']],
        ['02', 'Detect', ['Predictive fault', 'discovery and alerting']],
        ['03', 'Remediate', ['Onsite and remote', 'remediation']],
        ['04', 'Review', ['Scheduled patching', 'and architecture review']],
    ];
    $cardWidth = 218;
    $gap = 16;
    $top = 36;
    $height = 132;
    $mid = $top + ($height / 2);
@endphp

{{-- The one drawing on the page that shows a relationship rather than a
     structure: the cycle never stops, so the return path is the accent and the
     steps themselves are the ordinary hairlines. Nothing factual is added — the
     four steps are the four operating capabilities the site already publishes. --}}
<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 920 232" role="img"
     data-label-budget="24" style="--d-value-size: 12px"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    @foreach ($steps as $index => [$code, $name, $lines])
        @php
            $x = $index * ($cardWidth + $gap);
            $centre = $x + ($cardWidth / 2);
        @endphp

        <rect class="plate" x="{{ $x }}" y="{{ $top }}" width="{{ $cardWidth }}" height="{{ $height }}" />
        <path class="signal" d="M{{ $x + 20 }} {{ $top + 22 }} h16" />
        <text class="code" x="{{ $x + 44 }}" y="{{ $top + 26 }}">{{ $code }}</text>
        <text class="name" x="{{ $x + 20 }}" y="{{ $top + 54 }}">{{ $name }}</text>

        @foreach ($lines as $row => $line)
            <text class="value" x="{{ $x + 20 }}" y="{{ $top + 84 + ($row * 22) }}">{{ $line }}</text>
        @endforeach

        {{-- Direction of travel, one chevron between plates --}}
        @if ($index < count($steps) - 1)
            <path class="link" d="M{{ $x + $cardWidth + 1 }} {{ $mid }} h13 M{{ $x + $cardWidth + 9 }} {{ $mid - 4 }} l5 4 l-5 4" />
        @endif
    @endforeach

    {{-- The return: monitoring resumes the moment review ends --}}
    <path class="signal draw" pathLength="1" d="M811 {{ $top + $height }} V190 H109 V{{ $top + $height + 2 }}" />
    <path class="signal" d="M103 180 L109 172 L115 180" />
    <text class="foot" x="460" y="184" text-anchor="middle">CONTINUOUS</text>

    <text class="foot" x="0" y="222">OPERATIONS CYCLE · FOUR STEPS, OPERATED CONTINUOUSLY</text>
    <text class="foot" x="920" y="222" text-anchor="end">OPS-01</text>
</svg>
