@props([
    'id' => null,
    'rows' => [],
    'reference' => 'TECH-01',
    'title' => 'Reference drawing: the technology stack in operation, grouped into five domains. Cloud and virtualization, platforms and applications, security operations, identity and access, and edge and messaging.',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);

    // Set for the column this drawing sits in, not for the full page: 640 units
    // against a ~526px column renders the domain names at 11.5px and the values
    // at 10.3px, which stays legible because the values are short runs of capitals.
    // Value text starts at x=96 and is set at 12.5px monospace, measured at
    // 7.86 units per character, so 67 characters is the widest that leaves a
    // margin inside the plate — the longest value in the set is exactly 67.
    $plateHeight = 78;
    $pitch = 86;
    $height = ((count($rows) - 1) * $pitch) + $plateHeight + 34;
@endphp

<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 640 {{ $height }}" role="img"
     data-label-budget="67" style="--d-value-size: 12.5px"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    @foreach ($rows as $index => [$domain, $technologies])
        @php($y = $index * $pitch)

        <rect class="plate" x="0" y="{{ $y }}" width="640" height="{{ $plateHeight }}" />
        <path class="signal" d="M20 {{ $y + 26 }} h14" />
        <text class="code" x="44" y="{{ $y + 30 }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</text>
        <text class="name" x="96" y="{{ $y + 30 }}">{{ $domain }}</text>
        <text class="value" x="96" y="{{ $y + 58 }}">{{ $technologies }}</text>
    @endforeach

    <text class="foot" x="0" y="{{ $height - 10 }}">TECHNOLOGY IN OPERATION · FIVE DOMAINS</text>
    <text class="foot" x="640" y="{{ $height - 10 }}" text-anchor="end">{{ $reference }}</text>
</svg>
