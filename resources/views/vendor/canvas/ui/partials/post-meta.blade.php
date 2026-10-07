@php
    $publishedDate = $post->published_at->locale('pt')->translatedFormat('j \d\e F \d\e Y');
    $readingMinutes = max(1, (int) ceil(str_word_count(strip_tags((string) $post->body)) / 250));
@endphp
<div class="flex items-center justify-between gap-4 text-sm">
    @if ($post->user)
        @include('canvas::ui.partials.author', [
            'user' => $post->user,
            'imageClass' => 'h-9 w-9 text-xs',
            'linkClass' => 'font-medium text-slate-900',
            'subtitle' => $publishedDate,
        ])
    @else
        <time datetime="{{ $post->published_at->toDateString() }}" class="text-slate-500">{{ $publishedDate }}</time>
    @endif
    <span class="flex shrink-0 items-center gap-1.5 text-slate-500" title="Tempo de leitura">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="9"/>
            <path stroke-linecap="round" d="M12 7v5l3 2"/>
        </svg>
        {{ $readingMinutes }} min
        <span class="sr-only">de leitura</span>
    </span>
</div>
