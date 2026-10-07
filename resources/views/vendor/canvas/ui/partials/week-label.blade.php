<div class="flex flex-wrap items-center gap-2.5 text-sm">
    @if ($post->week_number && ! preg_match('/\bsemana\s+'.$post->week_number.'\b/iu', $post->title))
        <span class="rounded-md bg-brand-50 px-2 py-0.5 text-xs font-semibold text-brand-700">Semana {{ $post->week_number }}</span>
    @endif
    @if ($post->topic && ($showTopic ?? true))
        <a href="{{ route('canvas-ui.topic', $post->topic->slug) }}" class="font-medium text-slate-600 hover:text-brand-700">{{ $post->topic->name }}</a>
    @endif
</div>
