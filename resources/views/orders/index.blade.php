@extends('layouts.app')

@section('heading', 'Pedidos')
@section('subheading', 'Vendas registradas e baixa automática de estoque.')

@section('content')
<div class="panel tone-purple p-5 shadow-sm sm:p-6">
    <livewire:orders.order-search />
</div>
@endsection
