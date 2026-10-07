@extends('canvas::ui.layout')

@section('title', $topic->name . ' — ' . config('app.name'))

@push('head')
    @include('canvas::ui.partials.meta', [
        'title' => $topic->name,
        'description' => 'Relatos e progresso do desafio '.$topic->name.'.',
        'url' => route('canvas-ui.topic', $topic->slug),
        'type' => 'website',
    ])
@endpush

@section('content')
    <nav aria-label="Caminho" class="text-sm text-slate-500">
        <a href="{{ route('canvas-ui.topics') }}" class="hover:text-slate-900">&larr; Todos os desafios</a>
    </nav>

    <header class="mt-6 border-b border-slate-200 pb-10">
        @if ($topic->featured_image)
            <img src="{{ $topic->featured_image }}"
                 alt=""
                 class="mb-10 aspect-[21/9] w-full rounded-2xl object-cover"
                 decoding="async">
        @endif
        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
            <h1 class="font-serif text-4xl font-semibold leading-tight tracking-tight text-brand-900 sm:text-5xl">{{ $topic->name }}</h1>
            @include('canvas::ui.partials.challenge-status', ['topic' => $topic])
        </div>
        <p class="mt-3 text-lg text-slate-600">
            {{ $topic->posts_count }} {{ $topic->posts_count === 1 ? 'semana publicada' : 'semanas publicadas' }}
        </p>
    </header>

    <section aria-labelledby="semanas" class="mt-12">
        <h2 id="semanas" class="sr-only">Semanas</h2>
        @if ($posts->isNotEmpty())
            <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @include('canvas::ui.partials.post-card', ['post' => $post, 'showTopic' => false])
                @endforeach
            </div>
        @else
            <p class="py-16 text-center text-slate-500">Este desafio ainda não começou. O primeiro ponto de situação aparece aqui.</p>
        @endif

        <div class="mt-12">
            {{ $posts->links('canvas::ui.partials.pagination') }}
        </div>
    </section>
@endsection
