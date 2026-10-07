@php
    $statusLabels = ['current' => 'Em curso', 'done' => 'Concluído', 'upcoming' => 'Em breve'];
@endphp
<span @class([
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold',
    'bg-brand-50 text-brand-700' => $topic->status === 'current',
    'bg-emerald-50 text-emerald-700' => $topic->status === 'done',
    'bg-slate-100 text-slate-600' => $topic->status === 'upcoming',
])>{{ $statusLabels[$topic->status] }}</span>
