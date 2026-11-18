@extends('layouts.app')

@section('heading', $customer->name)
@section('subheading', 'Dados do cliente e pedidos vinculados')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <div class="panel tone-navy p-6 shadow-sm">
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-slate-500">E-mail</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $customer->email }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Telefone</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $customer->phone ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-slate-500">Cadastrado em</dt>
                <dd class="mt-1 font-medium text-slate-900">{{ $customer->created_at?->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('customers.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Voltar</a>
            <a href="{{ route('customers.edit', $customer) }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Editar</a>
        </div>
    </div>

    <div class="overflow-hidden panel tone-navy shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 text-sm font-semibold text-slate-900">Pedidos</div>
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-4 py-3">Pedido</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($customer->orders as $order)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('orders.show', $order) }}" class="font-medium text-indigo-600 hover:text-indigo-500">#{{ $order->id }}</a>
                            <p class="text-xs text-slate-400">{{ $order->created_at?->format('d/m/Y H:i') }}</p>
                        </td>
                        <td class="px-4 py-3">{{ \App\Models\Order::label($order->status) }}</td>
                        <td class="px-4 py-3 text-right">R$ {{ number_format($order->valor_total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-500">Nenhum pedido deste cliente.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
