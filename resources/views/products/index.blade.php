@extends('layouts.app')

@section('heading', 'Produtos')
@section('subheading', 'Consulte, cadastre e atualize o estoque.')

@section('content')
<div class="panel tone-blue p-5 shadow-sm sm:p-6">
    <livewire:products.product-search />
</div>
@endsection
