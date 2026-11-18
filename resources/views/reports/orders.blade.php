@extends('layouts.app')

@section('heading', 'Relatório de pedidos')
@section('subheading', 'Gerado em '.$generatedAt->format('d/m/Y H:i').' · '.$summary)

@section('content')
<form method="GET" class="no-print mb-4 flex flex-col gap-3 panel tone-purple p-4 shadow-sm lg:flex-row lg:items-end">
    <div>
        <label for="from" class="mb-1.5 block text-sm font-medium text-slate-700">De</label>
        <input type="date" name="from" id="from" value="{{ $from }}" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label for="to" class="mb-1.5 block text-sm font-medium text-slate-700">Até</label>
        <input type="date" name="to" id="to" value="{{ $to }}" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label for="status" class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
        <select name="status" id="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
            <option value="">Todos</option>
            @foreach (\App\Models\Order::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    @include('reports.partials.actions', ['pdfRoute' => 'reports.orders.pdf'])
</form>

<div class="overflow-hidden panel tone-purple shadow-sm">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-3">Pedido</th>
                <th class="px-4 py-3">Itens</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($orders as $order)
                <tr class="align-top">
                    <td class="px-4 py-3">
                        <a href="{{ route('orders.show', $order) }}" class="font-medium text-indigo-600 hover:text-indigo-500">#{{ $order->id }} · {{ $order->customer?->name ?? 'Cliente removido' }}</a>
                        <p class="text-xs text-slate-400">{{ $order->created_at?->format('d/m/Y H:i') }}</p>
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        @foreach ($order->items as $item)
                            <p>{{ $item->quantidade }}× {{ $item->product?->name ?? 'Produto' }}</p>
                        @endforeach
                    </td>
                    <td class="px-4 py-3">@include('partials.status-badge', ['status' => $order->status])</td>
                    <td class="px-4 py-3 text-right font-medium">R$ {{ number_format($order->valor_total, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">Nenhum pedido encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if ($orders->isNotEmpty())
        <div class="border-t border-slate-200 px-4 py-3 text-right text-sm font-semibold text-slate-900">
            Total (exceto cancelados): R$ {{ number_format($totalGeral, 2, ',', '.') }}
        </div>
    @endif
</div>
@endsection
