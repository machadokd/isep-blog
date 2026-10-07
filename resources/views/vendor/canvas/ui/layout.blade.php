<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/faveicon.png') }}">
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }}" href="{{ route('canvas-ui.feed') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap">
    @vite(['resources/css/app.css'])
    <style>
        a:focus-visible {
            outline: 2px solid #2457f5;
            outline-offset: 2px;
        }
    </style>
    @include('canvas::ui.partials.embeds')
    @stack('head')
</head>
@php
    $navItems = [
        ['route' => 'canvas-ui.index', 'active' => 'canvas-ui.index', 'label' => 'Início'],
        ['route' => 'canvas-ui.topics', 'active' => ['canvas-ui.topic*', 'canvas-ui.show'], 'label' => 'Desafios'],
        ['route' => 'canvas-ui.about', 'active' => 'canvas-ui.about', 'label' => 'Sobre nós'],
    ];
@endphp
<body class="flex min-h-screen flex-col bg-white font-sans text-slate-900 antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-100 bg-white/80 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-3 px-4">
            <a href="{{ route('canvas-ui.index') }}" class="shrink-0">
                <img src="{{ asset('images/finlogic_wout_bg.png') }}" alt="FinLogic" class="h-7 w-auto sm:h-8">
            </a>
            <nav aria-label="Principal" class="flex items-center gap-0.5 text-sm font-medium sm:gap-2">
                @foreach ($navItems as $item)
                    @php($isActive = request()->routeIs($item['active']))
                    <a href="{{ route($item['route']) }}"
                       @class([
                           'whitespace-nowrap rounded-lg px-2.5 py-2 transition sm:px-3',
                           'bg-brand-50 text-brand-700' => $isActive,
                           'text-slate-600 hover:bg-slate-50 hover:text-slate-900' => ! $isActive,
                       ])
                       @if ($isActive) aria-current="page" @endif>{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </header>

    @yield('hero')

    <main class="mx-auto w-full flex-1 px-4 max-w-6xl pb-20 pt-10">
        @yield('content')
    </main>

    <footer class="bg-slate-950 text-slate-400">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 py-10 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-semibold text-white">{{ config('app.name') }}</p>
                <p class="mt-1 text-sm">Mestrado em Engenharia de Inteligência Artificial no ISEP</p>
            </div>
            <nav aria-label="Rodapé" class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="hover:text-white">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>
        <div class="border-t border-slate-800">
            <p class="mx-auto max-w-6xl px-4 py-4 text-xs">&copy; {{ now()->year }} {{ config('app.name') }}</p>
        </div>
    </footer>
</body>
</html>
