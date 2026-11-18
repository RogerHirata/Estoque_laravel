@extends('layouts.app')

@section('heading', 'Novo pedido')
@section('subheading', 'O estoque do produto é baixado ao salvar.')

@section('content')
<div class="mx-auto max-w-2xl panel tone-purple p-6 shadow-sm">
    <form action="{{ route('orders.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="customer_id" class="mb-1.5 block text-sm font-medium text-slate-700">Cliente</label>
            <select name="customer_id" id="customer_id" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                <option value="" disabled selected>Selecione</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }} — {{ $customer->email }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="product_id" class="mb-1.5 block text-sm font-medium text-slate-700">Produto</label>
            <select name="product_id" id="product_id" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                <option value="" disabled selected>Selecione</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                        {{ $product->name }} — R$ {{ number_format($product->preco, 2, ',', '.') }} ({{ $product->estoque }} em estoque)
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="quantity" class="mb-1.5 block text-sm font-medium text-slate-700">Quantidade</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('orders.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Registrar pedido</button>
        </div>
    </form>
</div>
@endsection
