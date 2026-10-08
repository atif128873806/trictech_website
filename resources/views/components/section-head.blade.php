@props([
    'eyebrow' => null,
    'title' => null,
    'note' => null,
    'id' => null,
])

<div class="section-head">
    <div>
        @if ($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif
        @if ($title)
            <h2 class="h2 section-head__title" @if ($id) id="{{ $id }}" @endif>{{ $title }}</h2>
        @endif
    </div>
    <div>
        @if ($note)
            <p class="section-head__note">{{ $note }}</p>
        @endif
        {{ $slot }}
    </div>
</div>
