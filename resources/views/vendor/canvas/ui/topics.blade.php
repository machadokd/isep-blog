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
    <header class="grid gap-6 border-b border-slate-200 pb-12 pt-6 lg:grid-cols-[7fr_5fr] lg:items-center lg:gap-16 lg:pb-16">
        <h1 class="font-serif text-5xl font-semibold leading-[1.02] tracking-tight text-brand-900 sm:text-6xl lg:text-7xl">
            Os nossos desafios
        </h1>
        <p class="max-w-md text-lg leading-relaxed text-slate-600">
            Cada desafio é um problema real do setor financeiro que tentamos resolver com inteligência artificial.
            Trabalhamos num de cada vez, durante várias semanas, e publicamos aqui o ponto de situação de cada semana.
        </p>
    </header>

    @if ($topics->isNotEmpty())
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($topics as $topic)
                @include('canvas::ui.partials.topic-card', ['topic' => $topic, 'weeks' => $weeksByChallenge->get($topic->id, collect())])
            @endforeach
        </div>
    @else
        <p class="py-16 text-center text-slate-500">Ainda não há desafios.</p>
    @endif
@endsection
