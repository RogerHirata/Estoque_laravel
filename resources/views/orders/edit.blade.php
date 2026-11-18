@extends('layouts.app')

@section('heading', 'Editar pedido #'.$order->id)
@section('subheading', 'Alterar cliente, status ou quantidade. O estoque é recalculado.')

@section('content')
<div class="mx-auto max-w-2xl panel tone-purple p-6 shadow-sm">
    <form action="{{ route('orders.update', $order) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="customer_id" class="mb-1.5 block text-sm font-medium text-slate-700">Cliente</label>
            <select name="customer_id" id="customer_id" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" @selected(old('customer_id', $order->customer_id) == $customer->id)>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="mb-1.5 block text-sm font-medium text-slate-700">Status</label>
            <select name="status" id="status" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                @foreach (\App\Models\Order::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $order->status) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500">Cancelar devolve a quantidade ao estoque. Reativar o pedido baixa o estoque de novo.</p>
        </div>

        <div>
            <label for="quantity" class="mb-1.5 block text-sm font-medium text-slate-700">Quantidade</label>
            <input type="number" name="quantity" id="quantity" min="1" required value="{{ old('quantity', $order->items->first()?->quantidade ?? 1) }}" @disabled($order->items->count() !== 1) class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 disabled:bg-slate-50">
            @if ($order->items->count() !== 1)
                <input type="hidden" name="quantity" value="{{ $order->items->first()?->quantidade ?? 1 }}">
                <p class="mt-1 text-xs text-slate-500">Este pedido tem mais de um item. A quantidade de cada item é mantida.</p>
            @endif
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('orders.show', $order) }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Salvar pedido</button>
        </div>
    </form>
</div>
@endsection
