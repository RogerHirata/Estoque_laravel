@extends('layouts.app')

@section('heading', 'Editar produto')
@section('subheading', $product->name)

@section('content')
<div class="mx-auto max-w-2xl panel tone-blue p-6 shadow-sm">
    <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div>
            <label for="category_id" class="mb-1.5 block text-sm font-medium text-slate-700">Categoria</label>
            <select name="category_id" id="category_id" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="descricao" class="mb-1.5 block text-sm font-medium text-slate-700">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">{{ old('descricao', $product->descricao) }}</textarea>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="preco" class="mb-1.5 block text-sm font-medium text-slate-700">Preço (R$)</label>
                <input type="number" step="0.01" min="0" name="preco" id="preco" value="{{ old('preco', $product->preco) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div>
                <label for="estoque" class="mb-1.5 block text-sm font-medium text-slate-700">Estoque</label>
                <input type="number" min="0" name="estoque" id="estoque" value="{{ old('estoque', $product->estoque) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('products.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Salvar alterações</button>
        </div>
    </form>
</div>
@endsection
