@extends('layouts.app')

@section('heading', $product->name)
@section('subheading', 'Detalhes do produto')

@section('content')
<div class="mx-auto max-w-3xl panel tone-blue p-6 shadow-sm">
    <dl class="grid gap-5 sm:grid-cols-2">
        <div>
            <dt class="text-sm text-slate-500">Categoria</dt>
            <dd class="mt-1 font-medium text-slate-900">{{ $product->category?->name ?? 'Sem categoria' }}</dd>
        </div>
        <div>
            <dt class="text-sm text-slate-500">Preço</dt>
            <dd class="mt-1 font-medium text-slate-900">R$ {{ number_format($product->preco, 2, ',', '.') }}</dd>
        </div>
        <div>
            <dt class="text-sm text-slate-500">Estoque</dt>
            <dd class="mt-1 font-medium text-slate-900">{{ $product->estoque }}</dd>
        </div>
        <div>
            <dt class="text-sm text-slate-500">Cadastrado em</dt>
            <dd class="mt-1 font-medium text-slate-900">{{ $product->created_at?->format('d/m/Y H:i') }}</dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="text-sm text-slate-500">Descrição</dt>
            <dd class="mt-1 text-slate-800">{{ $product->descricao ?: 'Sem descrição.' }}</dd>
        </div>
    </dl>
    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('products.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Voltar</a>
        <a href="{{ route('products.edit', $product) }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Editar</a>
    </div>
</div>
@endsection
