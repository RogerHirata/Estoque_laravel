@extends('layouts.app')

@section('heading', 'Novo cliente')
@section('subheading', 'O e-mail precisa ser único.')

@section('content')
<div class="mx-auto max-w-xl panel tone-navy p-6 shadow-sm">
    <form action="{{ route('customers.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">E-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div>
            <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">Telefone</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('customers.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Salvar cliente</button>
        </div>
    </form>
</div>
@endsection
