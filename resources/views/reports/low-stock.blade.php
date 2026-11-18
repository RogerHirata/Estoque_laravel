@extends('layouts.app')

@section('heading', 'Relatório de estoque')
@section('subheading', 'Gerado em '.$generatedAt->format('d/m/Y H:i').' · '.$summary)

@section('content')
<form method="GET" class="no-print mb-4 flex flex-col gap-3 panel tone-orange p-4 shadow-sm lg:flex-row lg:items-end">
    <div class="w-full sm:max-w-xs">
        <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Categoria</label>
        <select name="category_id" id="category_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
            <option value="">Todas</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="minimum" class="mb-1.5 block text-sm font-medium text-slate-700">Estoque até</label>
        <input type="number" min="0" name="minimum" id="minimum" value="{{ $minimum }}" class="w-28 rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
    </div>
    @include('reports.partials.actions', ['pdfRoute' => 'reports.low-stock.pdf'])
</form>

<div class="overflow-hidden panel tone-orange shadow-sm">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-3">Produto</th>
                <th class="px-4 py-3">Categoria</th>
                <th class="px-4 py-3 text-right">Estoque</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($products as $product)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $product->name }}</td>
                    <td class="px-4 py-3">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->estoque > 0 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800' }}">{{ $product->estoque }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-slate-500">Nenhum produto dentro desse limite.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
