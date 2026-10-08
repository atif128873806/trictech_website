@props([
    'id' => null,
    'stages' => [],
    'title' => 'Reference drawing: the stages of the engagement, left to right, with the output of each stage.',
    'reference' => 'TL-01',
])

@php
    $uid = $id ?? 'sch' . substr(md5((string) mt_rand()), 0, 6);
    $count = max(count($stages), 1);

    // A single rail above the cards, one stem and one dot per stage, and a card
    // per stage. Detail lines are set at 12px monospace inside 218-unit cards,
    // which holds 25 characters; a feature test keeps future edits inside that.
    $cardWidth = 218;
    $gap = 16;
    $rail = 16;
    $cardTop = 34;
    $cardHeight = 152;
@endphp

{{-- Four cards across the frame leave less room than the other drawings, so the
     value size is set one step down to fit each line inside its plate. --}}
<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 920 246" role="img"
     data-label-budget="24" style="--d-value-size: 12px"
     aria-labelledby="tt-{{ $uid }}-title" preserveAspectRatio="xMidYMid meet">
    <title id="tt-{{ $uid }}-title">{{ $title }}</title>

    <path class="link" d="M18 {{ $rail }} H902" />

    @foreach ($stages as $index => $stage)
        @php
            $x = $index * ($cardWidth + $gap);
            $centre = $x + ($cardWidth / 2);
        @endphp

        <circle class="{{ $index === 0 ? 'dot' : 'dot--ring' }}" cx="{{ $centre }}" cy="{{ $rail }}" r="5" />
        <path class="link" d="M{{ $centre }} {{ $rail + 5 }} V{{ $cardTop }}" />

        <rect class="plate" x="{{ $x }}" y="{{ $cardTop }}" width="{{ $cardWidth }}" height="{{ $cardHeight }}" />
        <text class="code" x="{{ $x + 20 }}" y="{{ $cardTop + 28 }}">{{ $stage['code'] }}</text>
        <text class="name" x="{{ $x + 20 }}" y="{{ $cardTop + 56 }}">{{ $stage['name'] }}</text>

        @foreach ($stage['detail'] as $row => $line)
            <text class="value" x="{{ $x + 20 }}" y="{{ $cardTop + 84 + ($row * 22) }}">{{ $line }}</text>
        @endforeach

        <text class="code" x="{{ $x + 20 }}" y="{{ $cardTop + 136 }}">{{ $stage['output'] }}</text>
    @endforeach

    <text class="foot" x="0" y="236">STAGE SEQUENCE · LEFT TO RIGHT</text>
    <text class="foot" x="920" y="236" text-anchor="end">{{ $reference }}</text>
</svg>
