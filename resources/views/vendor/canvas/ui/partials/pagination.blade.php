@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginação" class="flex items-center justify-between gap-4 text-sm font-medium">
        <div>
            @unless ($paginator->onFirstPage())
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex h-11 items-center rounded-lg border border-slate-200 px-4 text-slate-700 transition hover:border-slate-300 hover:text-slate-900">&larr; Anterior</a>
            @endunless
        </div>

        <span class="text-slate-500">
            Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}
        </span>

        <div>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex h-11 items-center rounded-lg border border-slate-200 px-4 text-slate-700 transition hover:border-slate-300 hover:text-slate-900">Seguinte &rarr;</a>
            @endif
        </div>
    </nav>
@endif
