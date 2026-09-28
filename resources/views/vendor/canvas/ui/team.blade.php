@extends('canvas::ui.layout')

@section('title', 'Equipa — ' . config('app.name'))

@push('head')
    @include('canvas::ui.partials.meta', [
        'title' => 'Equipa',
        'description' => 'Conhece a equipa por trás de ' . config('app.name') . '.',
        'url' => route('canvas-ui.team'),
        'type' => 'website',
    ])
@endpush

@php
    $team = [
        [
            'name' => 'Rui Lapa',
            'email' => '1230883@isep.ipp.pt',
            'bio' =>
                'Estudante de Mestrado em Engenharia de Inteligência Artificial no ISEP, licenciado em Engenharia Informática na mesma instituição. Tenho particular interesse em Deep Learning, em especial na área da visão computacional, que pretendo aprofundar. Tenho experiência com Sistemas Multiagente, Retrieval-Augmented Generation (RAG) e Model Context Protocol (MCP).',
            'github' => '',
            'linkedin' => '',
            'photo' => 'images/team/rui_lapa.png',
        ],
        [
            'name' => 'Francisco Moreira',
            'email' => '1190576@isep.ipp.pt',
            'bio' =>
                'Estudante do primeiro ano do Mestrado em Engenharia de Inteligência Artificial no ISEP, licenciado em 2023 no curso de Engenharia Informática também no ISEP. Tenho experiência laboral de 2 anos em consultadoria trabalhando maioritariamente em análise e tratamento de dados usando tecnologias como Guidewire, MySQL, Java e SAP. Manifesto especial interesse nas áreas de Visão Computacional e Machine Learning, mais especificamente Predictive Analysis.',
            'github' => '',
            'linkedin' => '',
            'photo' => 'images/team/francisco_moreira.png',
        ],
        [
            'name' => 'João Machado',
            'email' => '1260433@isep.ipp.pt',
            'bio' =>
                'Licenciado em Engenharia Informática pela Universidade de Viana do Castelo, atualmente a estudar Mestrado em Engenharia de Inteligência Artificial. Fullstack Developer na Ankix com experiência em B2B e B2C. Focado em arquitetura de software, IoT e aplicações data-driven, com interesse em explorar machine learning em contextos de negócio real.',
            'github' => '',
            'linkedin' => '',
            'photo' => 'images/team/joao_machado.jpeg',
        ],
        [
            'name' => 'Pedro Ferreira',
            'email' => '1220274@isep.ipp.pt',
            'bio' =>
                'DevOps Engineer focado em infraestrutura, automação e resiliência de sistemas na SIBS. Fora do ecossistema tecnológico, valoriza momentos de convívio, atividades ao ar livre e exploração cultural, mantendo um perfil dinâmico e equilibrado.',
            'github' => '',
            'linkedin' => '',
            'photo' => 'images/team/pedro_ferreira.png',
        ],
        [
            'name' => 'Luis Couto',
            'email' => '1260438@isep.ipp.pt',
            'bio' => 'Estudante de Mestrado em Engenharia de Inteligência Artificial no ISEP, licenciado em Engenharia Informática pelo ISTEC Porto.

Tenho experiência no setor segurador, onde trabalhei como Underwriter em vários ramos. Tenho particular interesse pela Inteligência Artificial aplicada ao setor segurador, especialmente em áreas como modelos de risco e automação de processos de subscrição.',
            'github' => '',
            'linkedin' => '',
            'photo' => 'images/team/luis_couto.png',
        ],
        [
            'name' => 'Guilherme Mayor',
            'email' => '1230873@isep.ipp.pt',
            'bio' => 'Sou estudante de Mestrado em Engenharia de Inteligência Artificial no ISEP, onde também concluí a licenciatura em Engenharia Informática. Ao longo deste percurso, fui descobrindo o que mais me entusiasma: machine learning, IA generativa e agentes de IA. Não são, no entanto, interesses fechados, porque gosto de andar sempre à procura de coisas novas para explorar.
            Na prática, já trabalhei com agentes de IA, integração de LLMs, servidores MCP e IA generativa, aplicando-os em projetos de otimização. No campo académico, desenvolvi trabalhos de classificação e regressão com bibliotecas Python, usando redes neuronais, árvores de decisão e outras abordagens de machine learning.',
            'github' => '',
            'linkedin' => '',
            'photo' => 'images/team/guilherme.jpg',
        ],
    ];
@endphp

@section('content')
    <header class="mb-10">
        <h1 class="text-3xl font-bold">Equipa</h1>
        <p class="mt-2 text-gray-500">As pessoas por trás de {{ config('app.name') }}.</p>
    </header>

    <section class="mb-12">
        <h2 class="text-xl font-semibold text-gray-800">Sobre nós</h2>
        <div class="mt-3 space-y-4 text-gray-600 leading-relaxed">
            <p>A FinLogic é uma equipa dedicada ao desenvolvimento de soluções na área da Inteligência Artificial aplicada
                ao setor financeiro. No âmbito desta disciplina, iremos desenvolver um sistema pericial capaz de identificar
                o perfil de um investidor, tendo em conta fatores como a tolerância ao risco, objetivos financeiros e
                experiência de investimento.</p>
            <p>O objetivo do projeto é utilizar técnicas de Inteligência Artificial e sistemas baseados em regras para
                apoiar a classificação dos investidores e fornecer uma análise adequada ao seu perfil.</p>
            <p>A equipa é constituída por 6 elementos.</p>
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
        @foreach ($team as $member)
            <div class="border border-gray-100 rounded-lg p-6 flex flex-col items-center text-center">
                @if (!empty($member['photo']))
                    <img src="{{ asset($member['photo']) }}" alt="{{ $member['name'] }}"
                        class="h-20 w-20 rounded-full object-cover">
                @else
                    <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.2c-3.3 0-9.9 1.6-9.9 4.9v2.7h19.8v-2.7c0-3.3-6.6-4.9-9.9-4.9z" />
                        </svg>
                    </div>
                @endif

                <h2 class="mt-4 font-semibold text-gray-800">{{ $member['name'] ?: 'Nome do membro' }}</h2>
                @if ($member['email'])
                    <p class="text-sm text-gray-500">{{ $member['email'] }}</p>
                @endif
                @if ($member['bio'])
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed text-left">{{ $member['bio'] }}</p>
                @endif

                <div class="mt-3 flex items-center gap-3 text-sm text-gray-400">
                    @if ($member['github'])
                        <a href="{{ $member['github'] }}" target="_blank" rel="noopener"
                            class="hover:text-gray-700">GitHub</a>
                    @endif
                    @if ($member['linkedin'])
                        <a href="{{ $member['linkedin'] }}" target="_blank" rel="noopener"
                            class="hover:text-gray-700">LinkedIn</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        <a href="{{ route('canvas-ui.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            &larr; Voltar
        </a>
    </div>
@endsection
