<div>
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
        <input
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar pedido, cliente ou item"
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:max-w-sm"
        >
        <a href="{{ route('orders.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-auto">
            Novo pedido
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-3 py-3">Pedido</th>
                    <th class="px-3 py-3">Itens</th>
                    <th class="px-3 py-3 text-right">Total</th>
                    <th class="px-3 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    <tr wire:key="order-{{ $order->id }}" class="align-top hover:bg-slate-50">
                        <td class="px-3 py-3">
                            <p class="font-medium text-slate-900">#{{ $order->id }} · {{ $order->customer->name ?? 'Cliente removido' }}</p>
                            <p class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-400">
                                <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                @include('partials.status-badge', ['status' => $order->status])
                            </p>
                        </td>
                        <td class="px-3 py-3 text-slate-600">
                            @foreach($order->items as $item)
                                <p>{{ $item->quantidade }}× {{ $item->product->name ?? 'Produto' }}</p>
                            @endforeach
                        </td>
                        <td class="px-3 py-3 text-right font-semibold text-slate-900">R$ {{ number_format($order->valor_total, 2, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('orders.show', $order->id) }}" class="font-medium text-slate-600 hover:text-slate-900">Ver</a>
                                <a href="{{ route('orders.edit', $order->id) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Editar</a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Excluir este pedido e devolver o estoque?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-rose-600 hover:text-rose-500">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-10 text-center text-slate-500">Nenhum pedido encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p wire:loading wire:target="search" class="mt-3 text-sm text-slate-500">Buscando...</p>
</div>
