@php
    $weeks ??= collect();
    $latestWeek = $weeks->last();
@endphp
<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 transition hover:border-slate-300 hover:shadow-sm">
    <div class="relative aspect-[16/9] w-full overflow-hidden bg-brand-50">
        @if ($topic->featured_image)
            <img src="{{ $topic->featured_image }}"
                 alt=""
                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                 loading="lazy"
                 decoding="async">
        @else
            <span class="flex h-full w-full items-center justify-center font-serif text-7xl font-semibold text-brand-600" aria-hidden="true">
                {{ $topic->sequence }}
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-2 p-5">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-serif text-xl font-semibold text-slate-900">
                <a href="{{ route('canvas-ui.topic', $topic->slug) }}" class="after:absolute after:inset-0 group-hover:text-brand-700">
                    {{ $topic->name }}
                </a>
            </h2>
            <span class="shrink-0">
                @include('canvas::ui.partials.challenge-status', ['topic' => $topic])
            </span>
        </div>
        <p class="text-sm text-slate-600">
            {{ $topic->posts_count }} {{ $topic->posts_count === 1 ? 'semana publicada' : 'semanas publicadas' }}
        </p>

        @if ($latestWeek)
            <a href="{{ route('canvas-ui.show', $latestWeek->slug) }}"
               class="relative z-10 mt-auto block border-t border-slate-100 pt-4 text-sm hover:text-brand-700">
                <span class="text-slate-500">Última semana</span>
                <span class="mt-0.5 block font-medium text-slate-900">{{ \App\Support\PostTitle::withoutChallenge($latestWeek->title, $topic->name) }}</span>
            </a>
        @endif
    </div>
</article>
