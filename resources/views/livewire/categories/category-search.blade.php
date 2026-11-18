<div>
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
        <input
            type="search"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar categoria"
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 sm:max-w-sm"
        >
        <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-auto">
            Nova categoria
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-3 py-3">Nome</th>
                    <th class="px-3 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    <tr wire:key="category-{{ $category->id }}" class="hover:bg-slate-50">
                        <td class="px-3 py-3">
                            <p class="font-medium text-slate-900">{{ $category->name }}</p>
                            <p class="text-xs text-slate-400">#{{ $category->id }}</p>
                        </td>
                        <td class="px-3 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('categories.show', $category->id) }}" class="font-medium text-slate-600 hover:text-slate-900">Ver</a>
                                <a href="{{ route('categories.edit', $category->id) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Editar</a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Excluir esta categoria?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-rose-600 hover:text-rose-500">Excluir</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-3 py-10 text-center text-slate-500">Nenhuma categoria encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
