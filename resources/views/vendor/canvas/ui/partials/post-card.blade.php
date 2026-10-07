<article class="group flex flex-col">
    <a href="{{ route('canvas-ui.show', $post->slug) }}" class="block aspect-[16/10] overflow-hidden rounded-2xl bg-brand-50" tabindex="-1" aria-hidden="true">
        @if ($post->featured_image)
            <img src="{{ $post->featured_image }}"
                 alt=""
                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                 loading="lazy"
                 decoding="async">
        @else
            <span class="flex h-full items-center justify-center font-serif text-5xl font-semibold text-brand-600">
                {{ $post->week_number ? 'S'.$post->week_number : mb_substr($post->title, 0, 1) }}
            </span>
        @endif
    </a>

    <div class="mt-4 flex flex-1 flex-col gap-2">
        @include('canvas::ui.partials.week-label', ['post' => $post, 'showTopic' => $showTopic ?? true])
        <h3 class="font-serif text-xl font-semibold leading-snug text-slate-900">
            <a href="{{ route('canvas-ui.show', $post->slug) }}" class="hover:text-brand-700">{{ \App\Support\PostTitle::withoutChallenge($post->title, $post->topic?->name) }}</a>
        </h3>
        @if ($post->summary || $post->body)
            <p class="line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $post->summary ?: str(substr(html_entity_decode(strip_tags((string) $post->body)), 0, 1000))->squish()->limit(200) }}</p>
        @endif
        <div class="mt-auto pt-2">
            @include('canvas::ui.partials.post-meta', ['post' => $post])
        </div>
    </div>
</article>
