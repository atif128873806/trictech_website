@props([
    'id' => null,
    'title' => 'Reference drawing: three coverage layers of a managed environment. Network and edge, systems and identity, and cloud, data and recovery — each listing the controls operated at that layer.',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);

    // Three cards, one per layer, with one control per line. The previous
    // version packed fifteen controls into a divided cell grid, which read as a
    // spreadsheet: five columns of 11.5px text with a rule between every cell.
    // Space between the lines does the grouping now instead of borders.
    $layers = [
        ['NETWORK AND EDGE', ['MANAGED SD-WAN', 'CAMPUS ROUTING', 'ENTERPRISE WIRELESS', 'FIREWALL · IDS/IPS', 'ZERO TRUST ACCESS']],
        ['SYSTEMS AND IDENTITY', ['ACTIVE DIRECTORY', 'DNS · DHCP · GROUP POLICY', 'MICROSOFT 365 HYBRID', 'ENTRA ID · MFA', 'HYPER-V · KVM']],
        ['CLOUD, DATA AND RECOVERY', ['ELASTIC COMPUTE', 'PRIVATE STORAGE', 'CROSS-VOLUME BACKUP', 'IMMUTABLE VERSIONING', 'DB AND VM MIGRATION']],
    ];
    $cardWidth = 293;
    $gap = 20;
@endphp

<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 920 246" role="img"
     data-label-budget="32"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    @foreach ($layers as $index => [$name, $controls])
        @php($x = $index * ($cardWidth + $gap))
        <rect class="plate" x="{{ $x }}" y="0" width="{{ $cardWidth }}" height="200" />
        <path class="signal" d="M{{ $x + 22 }} 26 h16" />
        <text class="code" x="{{ $x + 46 }}" y="30">LAYER {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</text>
        <text class="name--sm name" x="{{ $x + 22 }}" y="58">{{ $name }}</text>

        @foreach ($controls as $row => $control)
            <text class="value" x="{{ $x + 22 }}" y="{{ 92 + ($row * 24) }}">{{ $control }}</text>
        @endforeach
    @endforeach

    <text class="foot" x="0" y="234">THREE LAYERS · ONE OPERATOR</text>
    <text class="foot" x="920" y="234" text-anchor="end">COV-01</text>
</svg>
