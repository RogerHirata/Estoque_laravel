@extends('layouts.app')

@section('heading', 'Pedido #'.$order->id)
@section('subheading', \App\Models\Order::label($order->status).' · '.($order->created_at?->format('d/m/Y H:i')))

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <div class="panel tone-purple p-6 shadow-sm">
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-slate-500">Cliente</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $order->customer?->name ?? 'Cliente removido' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Total</dt>
                <dd class="mt-1 font-medium text-slate-900">R$ {{ number_format($order->valor_total, 2, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Status</dt>
                <dd class="mt-1">@include('partials.status-badge', ['status' => $order->status])</dd>
            </div>
        </dl>
    </div>

    <div class="overflow-hidden panel tone-purple shadow-sm">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-4 py-3">Produto</th>
                    <th class="px-4 py-3 text-right">Qtd.</th>
                    <th class="px-4 py-3 text-right">Unitário</th>
                    <th class="px-4 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($order->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->product?->name ?? 'Produto removido' }}</td>
                        <td class="px-4 py-3 text-right">{{ $item->quantidade }}</td>
                        <td class="px-4 py-3 text-right">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('orders.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Voltar</a>
        <a href="{{ route('orders.edit', $order) }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Editar</a>
    </div>
</div>
@endsection
