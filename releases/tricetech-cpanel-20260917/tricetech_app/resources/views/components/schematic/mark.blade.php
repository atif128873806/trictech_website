@props(['variant' => 'network'])

{{-- Service marks: the same grammar as the drawings at mark scale — one or two
     plates, one accent signal, nothing else. Deliberately not an icon set:
     generic icons would say nothing about this business, and three strokes at
     72px say more than twelve. --}}
<svg {{ $attributes->merge(['class' => 'schematic']) }} viewBox="0 0 72 72" width="72" height="72" aria-hidden="true" focusable="false">
    @if ($variant === 'cloud')
        {{-- Stacked capacity with the cutover running through it --}}
        <rect class="plate" x="14" y="14" width="44" height="12" />
        <rect class="plate" x="14" y="30" width="44" height="12" />
        <rect class="plate" x="14" y="46" width="44" height="12" />
        <path class="signal" d="M8 50 V22" />
        <circle class="dot" cx="8" cy="22" r="3" />
    @elseif ($variant === 'edge')
        {{-- Enforced boundary with the protected side behind it --}}
        <path class="signal draw" pathLength="1" d="M8 36 H64" />
        <circle class="dot" cx="22" cy="36" r="3.5" />
        <circle class="dot" cx="36" cy="36" r="3.5" />
        <circle class="dot" cx="50" cy="36" r="3.5" />
        <path class="link" d="M14 56 H58" />
        <path class="link" d="M14 16 H58" />
    @else
        {{-- A managed network: nodes behind one edge --}}
        <rect class="plate" x="10" y="30" width="52" height="14" />
        <path class="link" d="M20 44 V54 M36 44 V54 M52 44 V54" />
        <circle class="dot--ring" cx="20" cy="58" r="3.5" />
        <circle class="dot--ring" cx="36" cy="58" r="3.5" />
        <circle class="dot--ring" cx="52" cy="58" r="3.5" />
        <path class="signal" d="M36 30 V14" />
        <circle class="dot" cx="36" cy="10" r="3.5" />
    @endif
</svg>
