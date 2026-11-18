@extends('layouts.app')

@section('heading', 'Categorias')
@section('subheading', 'Organize os produtos por tipo.')

@section('content')
<div class="panel tone-violet p-5 shadow-sm sm:p-6">
    <livewire:categories.category-search />
</div>
@endsection
