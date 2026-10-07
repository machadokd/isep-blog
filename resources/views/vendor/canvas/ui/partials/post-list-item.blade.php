<article class="max-w-3xl border-b border-slate-100 pb-8 last:border-0">
    @include('canvas::ui.partials.week-label', ['post' => $post])
    <h2 class="mt-2 font-serif text-2xl font-semibold leading-snug">
        <a href="{{ route('canvas-ui.show', $post->slug) }}" class="hover:text-brand-700">{{ \App\Support\PostTitle::withoutChallenge($post->title, $post->topic?->name) }}</a>
    </h2>
    @if ($post->summary || $post->body)
        <p class="mt-2 leading-relaxed text-slate-600">{{ $post->summary ?: str(substr(html_entity_decode(strip_tags((string) $post->body)), 0, 1000))->squish()->limit(200) }}</p>
    @endif
    <div class="mt-4">
        @include('canvas::ui.partials.post-meta', ['post' => $post])
    </div>
    @if (($showTags ?? false) && $post->relationLoaded('tags') && $post->tags->isNotEmpty())
        <div class="mt-3 flex flex-wrap gap-1.5">
            @foreach ($post->tags as $tag)
                <a href="{{ route('canvas-ui.tag', $tag->slug) }}"
                   class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-600 hover:bg-slate-200">{{ $tag->name }}</a>
            @endforeach
        </div>
    @endif
</article>
