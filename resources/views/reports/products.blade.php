@extends('layouts.app')

@section('heading', 'Relatório de produtos')
@section('subheading', 'Gerado em '.$generatedAt->format('d/m/Y H:i').' · '.$summary)

@section('content')
<form method="GET" class="no-print mb-4 flex flex-col gap-3 panel tone-blue p-4 shadow-sm sm:flex-row sm:items-end">
    <div class="w-full sm:max-w-xs">
        <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Categoria</label>
        <select name="category_id" id="category_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
            <option value="">Todas</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    @include('reports.partials.actions', ['pdfRoute' => 'reports.products.pdf'])
</form>

<div class="overflow-hidden panel tone-blue shadow-sm">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-3">Produto</th>
                <th class="px-4 py-3">Categoria</th>
                <th class="px-4 py-3 text-right">Preço</th>
                <th class="px-4 py-3 text-right">Estoque</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($products as $product)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $product->name }}</td>
                    <td class="px-4 py-3">{{ $product->category?->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">R$ {{ number_format($product->preco, 2, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right">
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->estoque > 5 ? 'bg-emerald-100 text-emerald-800' : ($product->estoque > 0 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">{{ $product->estoque }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">Nenhum produto encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
