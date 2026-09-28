<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" type="image/png" href="{{ asset('images/faveicon.png') }}">
    <link rel="alternate" type="application/rss+xml" title="{{ config('app.name') }}" href="{{ route('canvas-ui.feed') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Inter:wght@400;500;600&display=swap">
    @vite(['resources/css/app.css'])
    <style>
        a:focus-visible {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }
    </style>
    @include('canvas::ui.partials.embeds')
    @stack('head')
</head>
<body class="bg-white text-gray-900 antialiased font-sans">
    <header class="border-b border-gray-100 py-4 mb-8">
        <div class="max-w-3xl mx-auto px-4 flex items-center justify-between">
            <a href="{{ route('canvas-ui.index') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/finlogic_wout_bg.png') }}" alt="FinLogic" class="h-9 w-auto">
                {{-- <span class="hidden sm:block text-xs text-gray-400 border-l border-gray-200 pl-3">
                    Lógica que soma valor.
                </span> --}}
            </a>
            <nav class="flex items-center gap-4 text-sm text-gray-500">
                <a href="{{ route('canvas-ui.topics') }}" class="hover:text-gray-700">Desafios</a>
                {{-- <a href="{{ route('canvas-ui.tags') }}" class="hover:text-gray-700">Tags</a> --}}
                <a href="{{ route('canvas-ui.team') }}" class="hover:text-gray-700">Equipa</a>
            </nav>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 pb-16">
        @yield('content')
    </main>

    <footer class="border-t border-gray-100 py-8 mt-8">
        <div class="max-w-3xl mx-auto px-4 flex flex-col items-center gap-2 text-sm text-gray-400">
            <span class="font-medium text-gray-500">&copy; {{ now()->year }} FinLogic</span>
        </div>
    </footer>
</body>
</html>
