@props([
    'code' => null,
    'title' => null,
])

{{-- The strip that opens each band: sheet code, subject, and a hairline that
     runs to the margin, as on a drawing sheet. --}}
<div class="sheet-index" aria-hidden="true">
    @if ($code)
        <span class="sheet-index__code">{{ $code }}</span>
    @endif
    @if ($title)
        <span class="sheet-index__title">{{ $title }}</span>
    @endif
    <span class="sheet-index__rule"></span>
</div>
