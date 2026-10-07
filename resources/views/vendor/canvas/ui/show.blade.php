@extends('canvas::ui.layout')

@php
    $seo = \Canvas\Support\PostSeo::resolve($post, route('canvas-ui.show', $post->slug));
    $pageTitle = $seo['title'].' — '.config('app.name');
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $seo['title'],
        'description' => $seo['description'],
        'mainEntityOfPage' => $seo['canonical_url'],
        'datePublished' => $post->published_at?->toAtomString(),
        'dateModified' => $post->updated_at?->toAtomString(),
    ];
    if (filled($seo['image_url'])) {
        $jsonLd['image'] = [$seo['image_url']];
    }
    if ($post->user) {
        $jsonLd['author'] = [
            '@type' => 'Person',
            'name' => $post->user->name,
        ];
    }
@endphp

@section('title', $pageTitle)

@push('head')
    @include('canvas::ui.partials.meta', [
        'title' => $seo['title'],
        'description' => $seo['description'],
        'url' => $seo['canonical_url'],
        'type' => 'article',
        'image' => $seo['image_url'],
        'imageAlt' => $seo['image_alt'],
        'publishedTime' => $post->published_at?->toAtomString(),
        'modifiedTime' => $post->updated_at?->toAtomString(),
        'jsonLd' => $jsonLd,
    ])
@endpush

@section('content')
    <div class="mx-auto max-w-3xl">
    <nav aria-label="Caminho" class="text-sm text-slate-500">
        @if ($post->topic)
            <a href="{{ route('canvas-ui.topic', $post->topic->slug) }}" class="hover:text-slate-900">&larr; {{ $post->topic->name }}</a>
        @else
            <a href="{{ route('canvas-ui.index') }}" class="hover:text-slate-900">&larr; Início</a>
        @endif
    </nav>

    <header class="mt-6 border-b border-slate-200 pb-10">
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <h1 class="font-serif text-4xl font-semibold leading-tight tracking-tight text-brand-900 sm:text-5xl">{{ \App\Support\PostTitle::withoutChallenge($post->title, $post->topic?->name) }}</h1>
            @if ($post->week_number && ! preg_match('/\bsemana\s+'.$post->week_number.'\b/iu', $post->title))
                <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-semibold text-brand-700">Semana {{ $post->week_number }}</span>
            @endif
        </div>
        <p class="mt-3 text-lg text-slate-600">
            @if ($post->user)
                Escrito por {{ $post->user->name }} a
            @else
                Publicado a
            @endif
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->locale('pt')->translatedFormat('j \d\e F \d\e Y') }}</time>
        </p>
    </header>

    <div class="mt-12">
        <article>
            @if ($post->featured_image)
                <figure class="mb-10">
                    <img src="{{ $post->featured_image }}"
                         alt="{{ $post->featured_image_caption ?? $post->title }}"
                         class="w-full rounded-2xl"
                         decoding="async">
                    @if ($post->featured_image_caption)
                        <figcaption class="mt-2 text-center text-sm text-slate-500">
                            {{ $post->featured_image_caption }}
                        </figcaption>
                    @endif
                </figure>
            @endif

            @if ($post->summary)
                <aside class="mb-10 rounded-2xl border border-brand-100 bg-brand-50 p-5">
                    <p class="text-sm font-semibold text-brand-700">Resumo da semana</p>
                    <p class="mt-1 leading-relaxed text-slate-700">{{ $post->summary }}</p>
                </aside>
            @endif

            <div class="canvas-post-body prose prose-lg max-w-none font-serif prose-slate prose-headings:font-semibold prose-a:text-brand-600 hover:prose-a:text-brand-700">
                {!! $post->body !!}
            </div>

            @if ($post->tags->isNotEmpty())
                <footer class="mt-10 border-t border-slate-100 pt-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('canvas-ui.tag', $tag->slug) }}"
                               class="inline-block rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-600 hover:bg-slate-200">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </footer>
            @endif

            @if ($previousWeek || $nextWeek)
                <nav aria-label="Semana anterior e seguinte" class="mt-12 grid gap-4 sm:grid-cols-2">
                    @if ($previousWeek)
                        <a href="{{ route('canvas-ui.show', $previousWeek->slug) }}"
                           class="rounded-2xl border border-slate-200 p-5 transition hover:border-slate-300 hover:bg-slate-50">
                            <span class="text-sm text-slate-500">&larr; Semana {{ $previousWeek->week_number }}</span>
                            <span class="mt-1 block font-semibold text-slate-900">{{ \App\Support\PostTitle::withoutChallenge($previousWeek->title, $post->topic?->name) }}</span>
                        </a>
                    @endif
                    @if ($nextWeek)
                        <a href="{{ route('canvas-ui.show', $nextWeek->slug) }}"
                           class="rounded-2xl border border-slate-200 p-5 text-right transition hover:border-slate-300 hover:bg-slate-50 sm:col-start-2">
                            <span class="text-sm text-slate-500">Semana {{ $nextWeek->week_number }} &rarr;</span>
                            <span class="mt-1 block font-semibold text-slate-900">{{ \App\Support\PostTitle::withoutChallenge($nextWeek->title, $post->topic?->name) }}</span>
                        </a>
                    @endif
                </nav>
            @endif
        </article>
    </div>
    </div>
@endsection
