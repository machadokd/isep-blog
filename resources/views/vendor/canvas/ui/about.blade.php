@extends('canvas::ui.layout')

@section('title', 'Sobre nós — ' . config('app.name'))

@push('head')
    @include('canvas::ui.partials.meta', [
        'title' => 'Sobre nós',
        'description' => 'Conhece o projeto e a equipa por trás de ' . config('app.name') . '.',
        'url' => route('canvas-ui.about'),
        'type' => 'website',
    ])
@endpush


@php($team = config('team.members'))

@section('hero')
    <section class="bg-brand-900 text-white">
        <div class="mx-auto max-w-6xl px-4 py-16 lg:py-24">
            <div class="grid gap-10 lg:grid-cols-[5fr_7fr] lg:items-center lg:gap-16">
                <h1 class="font-serif text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl">
                    Inteligência artificial ao serviço das finanças
                </h1>
                <div class="space-y-4 text-lg leading-relaxed text-brand-100">
                    <p>A {{ config('app.name') }} é uma equipa de estudantes do Mestrado em Engenharia de Inteligência Artificial
                        do ISEP, dedicada ao desenvolvimento de soluções de Inteligência Artificial para o setor financeiro.</p>
                    <p>Ao longo do mestrado enfrentamos uma série de desafios, cada um focado num problema real da área. Neste
                        blog partilhamos, semana a semana, o progresso de cada desafio: o que fizemos, o que aprendemos e os
                        próximos passos.</p>
                </div>
            </div>

            <dl class="mt-14 grid border-t border-white/15 sm:grid-cols-2">
                @foreach ([
                    ['label' => 'Equipa', 'value' => count($team).' estudantes'],
                    ['label' => 'Curso', 'value' => 'Mestrado em Engenharia de IA no ISEP'],
                ] as $fact)
                    <div class="border-white/15 pt-6 [&:not(:first-child)]:mt-6 [&:not(:first-child)]:border-t sm:[&:not(:first-child)]:mt-0 sm:[&:not(:first-child)]:border-l sm:[&:not(:first-child)]:border-t-0 sm:[&:not(:first-child)]:pl-8">
                        <dt class="text-sm text-brand-200">{{ $fact['label'] }}</dt>
                        <dd class="mt-1 font-serif text-2xl font-semibold text-white">{{ $fact['value'] }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>
@endsection

@section('content')
    <section class="rounded-2xl bg-brand-50 px-4 py-10 sm:px-8 lg:px-10" aria-labelledby="equipa">
        <h2 id="equipa" class="font-serif text-3xl font-semibold text-brand-900">Equipa</h2>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($team as $member)
                <article class="flex flex-col rounded-2xl bg-white p-6 shadow-sm ring-1 ring-brand-100">
                    <div class="flex items-center gap-4">
                        @if (! empty($member['photo']))
                            <img src="{{ asset($member['photo']) }}" alt=""
                                 class="h-16 w-16 shrink-0 rounded-full object-cover ring-4 ring-brand-50"
                                 loading="lazy">
                        @else
                            <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-400" aria-hidden="true">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.2c-3.3 0-9.9 1.6-9.9 4.9v2.7h19.8v-2.7c0-3.3-6.6-4.9-9.9-4.9z" />
                                </svg>
                            </span>
                        @endif
                        <div class="min-w-0">
                            <h3 class="font-semibold text-slate-900">{{ $member['name'] }}</h3>
                            @if ($member['email'])
                                <a href="mailto:{{ $member['email'] }}" class="block truncate text-sm text-slate-500 hover:text-brand-700">{{ $member['email'] }}</a>
                            @endif
                        </div>
                    </div>

                    @if ($member['bio'])
                        <p id="bio-{{ $loop->index }}" class="mt-5 line-clamp-4 text-sm leading-relaxed text-slate-600">{{ $member['bio'] }}</p>
                        <button type="button"
                                class="mt-2 self-start text-sm font-medium text-brand-600 hover:text-brand-700"
                                aria-expanded="false"
                                aria-controls="bio-{{ $loop->index }}"
                                data-bio-toggle>Ler mais</button>
                    @endif

                    @if ($member['github'] || $member['linkedin'])
                        <div class="mt-auto flex gap-4 pt-5 text-sm font-medium text-slate-600">
                            @if ($member['github'])
                                <a href="{{ $member['github'] }}" target="_blank" rel="noopener" class="hover:text-slate-900">GitHub</a>
                            @endif
                            @if ($member['linkedin'])
                                <a href="{{ $member['linkedin'] }}" target="_blank" rel="noopener" class="hover:text-slate-900">LinkedIn</a>
                            @endif
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </section>

    <script>
        document.querySelectorAll('[data-bio-toggle]').forEach((button) => {
            const bio = document.getElementById(button.getAttribute('aria-controls'));

            if (bio.scrollHeight <= bio.clientHeight) {
                button.remove();
                return;
            }

            button.addEventListener('click', () => {
                const isExpanded = bio.classList.toggle('line-clamp-4') === false;
                button.setAttribute('aria-expanded', String(isExpanded));
                button.textContent = isExpanded ? 'Ler menos' : 'Ler mais';
            });
        });
    </script>
@endsection
