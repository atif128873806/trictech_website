@props([
    'id' => null,
    'title' => 'Reference drawing: the managed service stack, from facility and storage through compute and data to Oracle APEX applications, with the cutover path running across every layer.',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);

    // Five layers, one label pair each. The four bracketed control annotations
    // that used to sit in the right margin were removed: the page already
    // carries those controls, and the brackets added eight strokes and four
    // labels to a drawing that only needed to show the stack and the cutover.
    $layers = [
        ['Applications', 'ORACLE APEX · ERP WORKFLOWS · DASHBOARDS'],
        ['Data', 'MYSQL · SQL SERVER · POSTGRESQL'],
        ['Compute', 'KVM · HYPER-V · WINDOWS · RED HAT · HA'],
        ['Storage', 'CEPH · BLOCK · OBJECT · ARCHIVAL'],
        ['Facility', 'MISSISSAUGA FACILITY · POWER · NETWORK'],
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 640 344" role="img"
     data-label-budget="60"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    {{-- The cutover path crosses every layer --}}
    <path class="signal draw" pathLength="1" d="M44 46 V266" />
    <circle class="dot--ring" cx="44" cy="46" r="5" />
    <circle class="dot" cx="44" cy="266" r="5" />
    <text class="code" x="30" y="156" text-anchor="middle" transform="rotate(-90 30 156)">CUTOVER PATH</text>

    @foreach ($layers as $index => [$name, $detail])
        @php($y = $index * 68)
        <rect class="plate" x="76" y="{{ $y }}" width="564" height="58" />
        <text class="name" x="100" y="{{ $y + 24 }}">{{ $name }}</text>
        <text class="value" x="100" y="{{ $y + 45 }}">{{ $detail }}</text>
        <text class="code" x="620" y="{{ $y + 24 }}" text-anchor="end">L{{ 5 - $index }}</text>
    @endforeach

    <text class="foot" x="76" y="334">SPECIFICATION · MANAGED CLOUD SERVICE STACK</text>
    <text class="foot" x="640" y="334" text-anchor="end">STACK-01</text>
</svg>
