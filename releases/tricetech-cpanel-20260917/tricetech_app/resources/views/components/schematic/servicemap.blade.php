@props([
    'id' => null,
    'items' => [],
    'columns' => 3,
    'title' => 'Reference drawing: the published service areas of this family, laid out as a map.',
    'reference' => 'MAP-01',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);
    $count = max(count($items), 1);
    $columns = max((int) $columns, 1);
    $gap = 16;
    $tileWidth = (int) floor((920 - (($columns - 1) * $gap)) / $columns);
    $tileHeight = 92;
    $rows = (int) ceil($count / $columns);
    $height = ($rows * $tileHeight) + (($rows - 1) * $gap) + 40;
@endphp

<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 920 {{ $height }}" role="img"
     data-label-budget="{{ (int) floor(($tileWidth - 34) / 7.8) }}"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    @foreach ($items as $index => $item)
        @php
            $col = $index % $columns;
            $row = (int) floor($index / $columns);
            $x = $col * ($tileWidth + $gap);
            $y = $row * ($tileHeight + $gap);
        @endphp

        <rect class="plate" x="{{ $x }}" y="{{ $y }}" width="{{ $tileWidth }}" height="{{ $tileHeight }}" />
        <path class="signal" d="M{{ $x + 22 }} {{ $y + 26 }} h14" />
        <text class="code" x="{{ $x + 46 }}" y="{{ $y + 30 }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</text>
        <text class="name name--sm" x="{{ $x + 22 }}" y="{{ $y + 58 }}">{{ $item[0] }}</text>
        <text class="value" x="{{ $x + 22 }}" y="{{ $y + 80 }}">{{ $item[1] }}</text>
    @endforeach

    <text class="foot" x="0" y="{{ $height - 6 }}">SERVICE MAP · {{ str_pad($count, 2, '0', STR_PAD_LEFT) }} PUBLISHED AREAS</text>
    <text class="foot" x="920" y="{{ $height - 6 }}" text-anchor="end">{{ $reference }}</text>
</svg>
