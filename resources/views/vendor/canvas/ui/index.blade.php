@extends('canvas::ui.layout')

@section('title', config('app.name'))

@push('head')
    @include('canvas::ui.partials.meta', [
        'title' => config('app.name'),
        'description' => 'Pontos de situação semanais dos desafios de Inteligência Artificial da equipa '.config('app.name').'.',
        'url' => route('canvas-ui.index'),
        'type' => 'website',
    ])
@endpush

@php
    $isLanding = $posts->onFirstPage() && ! $selectedChallenge;
@endphp

@section('hero')
    @if ($isLanding)
        <section class="bg-brand-900 text-white">
            <div class="mx-auto grid max-w-6xl gap-14 px-4 py-16 lg:grid-cols-[7fr_5fr] lg:items-center lg:py-24">
                <div>
                    <h1 class="max-w-2xl font-serif text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                        Inteligência artificial aplicada às finanças, semana a semana
                    </h1>
                    <p class="mt-6 max-w-xl text-lg leading-relaxed text-brand-100">
                        Somos estudantes do Mestrado em Engenharia de Inteligência Artificial do ISEP.
                        Todas as semanas publicamos o ponto de situação do desafio em que estamos a trabalhar.
                    </p>
                    <div class="mt-9 flex flex-wrap gap-3">
                        <a href="{{ route('canvas-ui.topics') }}"
                           class="inline-flex h-12 items-center rounded-lg bg-white px-6 text-sm font-semibold text-brand-900 transition hover:bg-brand-50">
                            Ver desafios
                        </a>
                    </div>
                </div>

                @if ($challenges->isNotEmpty())
                    @php
                        $finishedChallenges = $challenges->where('status', 'done')->values();
                        $upcomingChallenges = $challenges->where('status', 'upcoming')->values();
                        $hiddenFinishedCount = max(0, $finishedChallenges->count() - 1);
                        $hiddenUpcomingCount = max(0, $upcomingChallenges->count() - 1);
                        $hiddenChallenges = $finishedChallenges->take($hiddenFinishedCount)->concat($upcomingChallenges->slice(1));
                        $pathChallenges = $challenges->reject(fn ($challenge) => $hiddenChallenges->contains($challenge));
                    @endphp
                    <section aria-labelledby="percurso">
                        <h2 id="percurso" class="sr-only">Percurso dos desafios</h2>
                        <ol class="divide-y divide-white/10 border-y border-white/10">
                            @if ($hiddenFinishedCount > 0)
                                <li class="py-3 text-sm text-brand-200">
                                    {{ $hiddenFinishedCount }} {{ $hiddenFinishedCount === 1 ? 'desafio concluído' : 'desafios concluídos' }}
                                </li>
                            @endif
                            @foreach ($pathChallenges as $challenge)
                                @php
                                    $isCurrent = $challenge->status === 'current';
                                @endphp
                                <li class="grid grid-cols-[2.75rem_minmax(0,1fr)_auto] items-baseline gap-x-4 py-4">
                                    <span @class([
                                        'font-serif text-xl tabular-nums',
                                        'text-white' => $isCurrent,
                                        'text-brand-200' => ! $isCurrent,
                                    ])>{{ str_pad((string) $challenge->sequence, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span @class([
                                        'text-lg font-semibold text-white' => $isCurrent,
                                        'text-brand-100' => $challenge->status === 'done',
                                        'text-brand-200' => $challenge->status === 'upcoming',
                                    ])>{{ $challenge->name }}</span>
                                    <span class="whitespace-nowrap text-right text-sm text-brand-200">
                                        @if ($isCurrent)
                                            <span class="inline-flex items-center gap-2 font-medium text-white">
                                                <span class="h-2 w-2 rounded-full bg-brand-400" aria-hidden="true"></span>
                                                Em curso
                                            </span>
                                        @elseif ($challenge->status === 'done')
                                            {{ $challenge->posts_count }} {{ $challenge->posts_count === 1 ? 'semana' : 'semanas' }}
                                        @else
                                            A seguir
                                        @endif
                                    </span>

                                    @if ($isCurrent && $currentChallengeWeeks->isNotEmpty())
                                        @php
                                            $hiddenWeekCount = max(0, $currentChallengeWeeks->count() - 3);
                                            $visibleWeeks = $currentChallengeWeeks->slice($hiddenWeekCount);
                                        @endphp
                                        <ol class="col-span-3 mt-4 flex gap-2">
                                            @if ($hiddenWeekCount > 0)
                                                <li class="hidden shrink-0 items-center rounded-lg px-3 py-2 text-sm text-brand-200 ring-1 ring-inset ring-white/10 sm:flex">
                                                    + {{ $hiddenWeekCount }}
                                                    <span class="sr-only">{{ $hiddenWeekCount === 1 ? 'semana anterior' : 'semanas anteriores' }}</span>
                                                </li>
                                            @endif
                                            @foreach ($visibleWeeks as $index => $week)
                                                @php
                                                    $isLatestWeek = $loop->last;
                                                @endphp
                                                <li @class([
                                                    'min-w-0 max-w-[8.5rem] flex-1 rounded-lg px-3 py-2 text-sm',
                                                    'bg-white text-brand-900' => $isLatestWeek,
                                                    'bg-white/5 text-brand-100 ring-1 ring-inset ring-white/10' => ! $isLatestWeek,
                                                ])>
                                                    <span class="block whitespace-nowrap font-semibold">Semana {{ $index + 1 }}</span>
                                                    <time datetime="{{ $week->published_at->toDateString() }}" @class([
                                                        'block whitespace-nowrap text-xs',
                                                        'text-slate-600' => $isLatestWeek,
                                                        'text-brand-200' => ! $isLatestWeek,
                                                    ])>{{ $week->published_at->locale('pt')->translatedFormat('j M') }}</time>
                                                </li>
                                            @endforeach
                                        </ol>
                                    @endif
                                </li>
                            @endforeach
                            @if ($hiddenUpcomingCount > 0)
                                <li class="py-3 text-sm text-brand-200">
                                    + {{ $hiddenUpcomingCount }} {{ $hiddenUpcomingCount === 1 ? 'desafio por começar' : 'desafios por começar' }}
                                </li>
                            @endif
                        </ol>
                    </section>
                @endif
            </div>
        </section>
    @endif
@endsection

@section('content')
    @if ($isLanding && $featuredPost)
        <section aria-labelledby="ultima-atualizacao" class="pt-6">
            <h2 id="ultima-atualizacao" class="font-serif text-3xl font-semibold">Última atualização</h2>
            <article class="group mt-8 grid items-center gap-8 md:grid-cols-[7fr_5fr] md:gap-12">
                <a href="{{ route('canvas-ui.show', $featuredPost->slug) }}" class="block aspect-[16/10] overflow-hidden rounded-2xl bg-brand-50" tabindex="-1" aria-hidden="true">
                    @if ($featuredPost->featured_image)
                        <img src="{{ $featuredPost->featured_image }}"
                             alt=""
                             class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                             decoding="async">
                    @else
                        <span class="flex h-full items-center justify-center font-serif text-7xl font-semibold text-brand-600">
                            S{{ $featuredPost->week_number }}
                        </span>
                    @endif
                </a>
                <div class="flex flex-col gap-4">
                    @include('canvas::ui.partials.week-label', ['post' => $featuredPost])
                    <h3 class="font-serif text-3xl font-semibold leading-tight lg:text-4xl">
                        <a href="{{ route('canvas-ui.show', $featuredPost->slug) }}" class="hover:text-brand-700">{{ \App\Support\PostTitle::withoutChallenge($featuredPost->title, $featuredPost->topic?->name) }}</a>
                    </h3>
                    @if ($featuredPost->summary || $featuredPost->body)
                        <p class="text-lg leading-relaxed text-slate-600">{{ $featuredPost->summary ?: str(substr(html_entity_decode(strip_tags((string) $featuredPost->body)), 0, 1000))->squish()->limit(200) }}</p>
                    @endif
                    <div class="pt-2">
                        @include('canvas::ui.partials.post-meta', ['post' => $featuredPost])
                    </div>
                </div>
            </article>
        </section>
    @endif

    <section aria-labelledby="pontos-de-situacao" @class(['mt-20 border-t border-slate-200 pt-16' => $isLanding && $featuredPost])>
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <h2 id="pontos-de-situacao" class="font-serif text-3xl font-semibold">
                @if ($selectedChallenge)
                    {{ $selectedChallenge->name }}
                @elseif ($featuredPost)
                    Semanas anteriores
                @else
                    Pontos de situação
                @endif
            </h2>

            @if ($challenges->where('posts_count', '>', 0)->count() > 1 || $selectedChallenge)
                <nav aria-label="Filtrar por desafio" class="flex flex-wrap gap-2">
                    <a href="{{ route('canvas-ui.index') }}"
                       @class([
                           'inline-flex h-11 items-center rounded-full border px-4 text-sm font-medium transition',
                           'border-slate-900 bg-slate-900 text-white' => ! $selectedChallenge,
                           'border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900' => $selectedChallenge,
                       ])
                       @if (! $selectedChallenge) aria-current="page" @endif>Todos</a>
                    @foreach ($challenges->where('posts_count', '>', 0) as $challenge)
                        @php $isSelected = $selectedChallenge?->is($challenge); @endphp
                        <a href="{{ route('canvas-ui.index', ['desafio' => $challenge->slug]) }}"
                           @class([
                               'inline-flex h-11 items-center rounded-full border px-4 text-sm font-medium transition',
                               'border-slate-900 bg-slate-900 text-white' => $isSelected,
                               'border-slate-200 text-slate-600 hover:border-slate-300 hover:text-slate-900' => ! $isSelected,
                           ])
                           @if ($isSelected) aria-current="page" @endif>
                            <span @class([
                                'mr-2 inline-flex h-5 w-5 items-center justify-center rounded-full text-xs font-semibold',
                                'bg-white/20 text-white' => $isSelected,
                                'bg-slate-100 text-slate-600' => ! $isSelected,
                            ]) aria-hidden="true">{{ $challenge->sequence }}</span>
                            <span class="sr-only">Desafio {{ $challenge->sequence }}:</span>
                            {{ $challenge->name }}
                        </a>
                    @endforeach
                </nav>
            @endif
        </div>

        @if ($posts->isNotEmpty())
            <div class="mt-10 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    @include('canvas::ui.partials.post-card', ['post' => $post])
                @endforeach
            </div>
        @elseif (! $featuredPost)
            <p class="py-16 text-slate-500">Ainda não há pontos de situação publicados.</p>
        @endif

        <div class="mt-12">
            {{ $posts->links('canvas::ui.partials.pagination') }}
        </div>
    </section>

    @if ($isLanding)
        @php $team = config('team.members', []); @endphp
        <section class="mt-20 rounded-2xl bg-brand-50 px-6 py-10 sm:px-10" aria-labelledby="equipa">
            <div class="grid items-center gap-8 lg:grid-cols-[1fr_auto]">
                <div>
                    <h2 id="equipa" class="font-serif text-3xl font-semibold text-brand-900">Quem escreve</h2>
                    <p class="mt-3 max-w-xl leading-relaxed text-slate-700">
                        {{ count($team) }} estudantes do Mestrado em Engenharia de Inteligência Artificial do ISEP,
                        com percursos em consultoria, DevOps, seguros e desenvolvimento de software.
                    </p>
                    <a href="{{ route('canvas-ui.about') }}"
                       class="mt-6 inline-flex h-11 items-center rounded-lg bg-brand-600 px-5 text-sm font-semibold text-white transition hover:bg-brand-700">
                        Conhecer a equipa
                    </a>
                </div>
                <ul class="flex flex-wrap gap-4 sm:gap-5">
                    @foreach ($team as $member)
                        <li class="flex w-20 flex-col items-center gap-2 text-center">
                            <img src="{{ asset($member['photo']) }}" alt="" class="h-16 w-16 rounded-full object-cover ring-4 ring-white" loading="lazy">
                            <span class="text-xs font-medium leading-tight text-slate-700">{{ $member['name'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif
@endsection
