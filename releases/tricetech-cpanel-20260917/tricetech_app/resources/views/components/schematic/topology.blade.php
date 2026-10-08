@props([
    'id' => null,
    'title' => 'Reference drawing: a Mississauga head office and a regional office joined by an encrypted site-to-site tunnel to a managed internet edge, with 24/7 monitoring across both sites.',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);

    // Four plates and one accent path. The previous version of this drawing
    // carried a drafting grid, sheet zone letters, a second frame, callout
    // leaders and two rotated tunnel labels; measured review showed those
    // strokes were competing with the labels they were meant to support.
    $sites = [
        ['Head office', 'Mississauga', ['CORE SWITCHING · VLAN', 'SERVERS · KVM / HYPER-V', 'WIRELESS · 802.1X'], 20],
        ['Regional office', 'Ontario', ['EDGE ROUTER · SD-WAN', 'ACCESS LAYER', 'REMOTE CLIENTS · VPN'], 324],
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 640 372" role="img"
     data-label-budget="32"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    {{-- Managed internet edge --}}
    <rect class="plate" x="20" y="0" width="600" height="76" />
    <path class="signal" d="M40 24 H52" />
    <text class="code" x="64" y="28">EDGE-01</text>
    <text class="name" x="40" y="54">Managed internet edge</text>
    <text class="value" x="600" y="54" text-anchor="end">FIREWALL · IDS/IPS · EMAIL EDGE</text>

    {{-- The encrypted path down to both sites --}}
    <path class="signal draw" pathLength="1" d="M320 76 V104 M320 104 H136 V150 M320 104 H504 V150" />
    <circle class="dot" cx="320" cy="104" r="4" />
    <circle class="dot" cx="136" cy="120" r="4" />
    <circle class="dot" cx="504" cy="120" r="4" />
    <text class="value" x="330" y="133">SITE-TO-SITE · IPSEC · TLS 1.3</text>

    {{-- The two sites --}}
    @foreach ($sites as [$name, $region, $lines, $x])
        <rect class="plate" x="{{ $x }}" y="150" width="296" height="158" />
        <text class="name" x="{{ $x + 24 }}" y="184">{{ $name }}</text>
        <text class="code" x="{{ $x + 272 }}" y="184" text-anchor="end">{{ $region }}</text>
        @foreach ($lines as $index => $line)
            <text class="value" x="{{ $x + 24 }}" y="{{ 216 + ($index * 26) }}">{{ $line }}</text>
        @endforeach
    @endforeach

    {{-- Monitoring, running under both sites --}}
    <rect class="plate--soft" x="20" y="326" width="600" height="46" />
    <circle class="dot" cx="52" cy="349" r="4" />
    {{-- Full width under both sites, so this is a rail caption rather than an
         in-plate value: the declared label budget covers plate text. --}}
    <text class="foot" x="72" y="353">24/7 MONITORING · PREDICTIVE FAULTS · SCHEDULED PATCHING</text>
</svg>
