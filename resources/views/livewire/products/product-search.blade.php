<div>
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative w-full sm:max-w-sm">
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nome"
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
            >
        </div>
        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-auto">
            Novo produto
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-3 py-3">Produto</th>
                    <th class="px-3 py-3">Categoria</th>
                    <th class="px-3 py-3 text-right">Preço</th>
                    <th class="px-3 py-3 text-right">Estoque</th>
                    <th class="px-3 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr wire:key="product-{{ $product->id }}" class="hover:bg-slate-50">
                        <td class="px-3 py-3">
                            <p class="font-medium text-slate-900">{{ $product->name }}</p>
                            <p class="text-xs text-slate-400">#{{ $product->id }}</p>
                        </td>
                        <td class="px-3 py-3 text-slate-600">{{ $product->category->name ?? 'Sem categoria' }}</td>
                        <td class="px-3 py-3 text-right font-medium text-slate-900">R$ {{ number_format($product->preco, 2, ',', '.') }}</td>
                        <td class="px-3 py-3 text-right">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->estoque > 5 ? 'bg-emerald-50 text-emerald-700' : ($product->estoque > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                {{ $product->estoque }}
                            </span>
                        </td>
                        <td class="px-3 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('products.show', $product->id) }}" class="font-medium text-slate-600 hover:text-slate-900">Ver</a>
                                <a href="{{ route('products.edit', $product->id) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Editar</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Excluir este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-rose-600 hover:text-rose-500">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-10 text-center text-slate-500">Nenhum produto encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
