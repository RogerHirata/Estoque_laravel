@extends('layouts.app')

@section('heading', 'Editar categoria')
@section('subheading', $category->name)

@section('content')
<div class="mx-auto max-w-xl panel tone-violet p-6 shadow-sm">
    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
        </div>
        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('categories.index') }}" class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancelar</a>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500">Salvar alterações</button>
        </div>
    </form>
</div>
@endsection
