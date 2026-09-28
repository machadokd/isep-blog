@extends('canvas::ui.layout')

@section('title', 'Desafios — ' . config('app.name'))

@push('head')
    @include('canvas::ui.partials.meta', [
        'title' => 'Desafios',
        'description' => 'Explora os desafios do '.config('app.name').'.',
        'url' => route('canvas-ui.topics'),
        'type' => 'website',
    ])
@endpush

@section('content')
    <header class="mb-10">
        <h1 class="text-3xl font-bold">Desafios</h1>
        <p class="mt-2 text-gray-500">Explora os desafios do {{ config('app.name') }}.</p>
    </header>

    @forelse ($topics as $topic)
        @if ($loop->first)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @endif

        <a href="{{ route('canvas-ui.topic', $topic->slug) }}"
           class="group flex flex-col overflow-hidden rounded-lg border border-gray-100 transition hover:border-gray-200 hover:shadow-sm">
            <div class="aspect-[16/9] w-full overflow-hidden bg-gradient-to-br from-indigo-50 to-gray-100">
                @if ($topic->featured_image)
                    <img src="{{ $topic->featured_image }}"
                         alt="{{ $topic->featured_image_caption ?? $topic->name }}"
                         class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                         loading="lazy"
                         decoding="async">
                @else
                    <div class="flex h-full w-full items-center justify-center text-indigo-200">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 12.75V4.5A2.25 2.25 0 015.25 2.25h13.5A2.25 2.25 0 0121 4.5v14.25A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V12.75z"/>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="flex flex-1 items-center justify-between gap-3 p-4">
                <span class="font-semibold text-gray-800 group-hover:text-indigo-600 truncate">
                    {{ $topic->name }}
                </span>
                <span class="shrink-0 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500">
                    {{ $topic->posts_count }} {{ str('post')->plural($topic->posts_count) }}
                </span>
            </div>
        </a>

        @if ($loop->last)
            </div>
        @endif
    @empty
        <p class="text-gray-500 text-center py-16">Ainda não há desafios.</p>
    @endforelse

    <div class="mt-8">
        {{ $topics->links('canvas::ui.partials.pagination') }}
    </div>

    <div class="mt-8">
        <a href="{{ route('canvas-ui.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; All posts
        </a>
    </div>
@endsection
