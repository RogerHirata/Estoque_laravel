@extends('layouts.app')

@section('heading', $category->name)
@section('subheading', 'Produtos desta categoria')

@section('content')
<div class="mx-auto max-w-3xl space-y-4">
    <div class="flex justify-end gap-2">
        <a href="{{ route('categories.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Voltar</a>
        <a href="{{ route('categories.edit', $category) }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Editar</a>
    </div>
    <div class="overflow-hidden panel tone-violet shadow-sm">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <th class="px-4 py-3">Produto</th>
                    <th class="px-4 py-3 text-right">Preço</th>
                    <th class="px-4 py-3 text-right">Estoque</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($category->products as $product)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('products.show', $product) }}" class="font-medium text-indigo-600 hover:text-indigo-500">{{ $product->name }}</a>
                        </td>
                        <td class="px-4 py-3 text-right">R$ {{ number_format($product->preco, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ $product->estoque }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-slate-500">Nenhum produto nesta categoria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
