@extends('layouts.app')

@section('heading', 'Clientes')
@section('subheading', 'Cadastro usado na emissão de pedidos.')

@section('content')
<div class="panel tone-navy p-5 shadow-sm sm:p-6">
    <livewire:customers.customer-search />
</div>
@endsection
