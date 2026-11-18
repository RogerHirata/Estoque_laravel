@php
    $tone = match ($status) {
        'completed' => 'bg-emerald-100 text-emerald-800',
        'pendente' => 'bg-amber-100 text-amber-800',
        'cancelado' => 'bg-rose-100 text-rose-800',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp
<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $tone }}">{{ \App\Models\Order::label($status) }}</span>
