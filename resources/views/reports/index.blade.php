@extends('layouts.app')

@section('heading', 'Relatórios')
@section('subheading', 'Filtre os dados do banco, imprima ou exporte em PDF.')

@section('content')
<div class="grid gap-4 md:grid-cols-2">
    @foreach ([
        ['route' => 'reports.products', 'title' => 'Estoque de produtos', 'text' => 'Nome, categoria, preço e quantidade. Filtre por categoria.', 'tone' => 'stat-products'],
        ['route' => 'reports.low-stock', 'title' => 'Estoque', 'text' => 'Produtos por categoria e limite de quantidade.', 'tone' => 'stat-low'],
        ['route' => 'reports.customers', 'title' => 'Clientes', 'text' => 'Nome, e-mail e telefone, com filtro por data de cadastro.', 'tone' => 'stat-customers'],
        ['route' => 'reports.orders', 'title' => 'Pedidos', 'text' => 'Itens, status e valor total. Filtre por período e status.', 'tone' => 'stat-orders'],
    ] as $report)
        <a href="{{ route($report['route']) }}" class="stat-card {{ $report['tone'] }}">
            <h2 class="text-base font-semibold">{{ $report['title'] }}</h2>
            <p class="stat-hint mt-1 text-sm">{{ $report['text'] }}</p>
        </a>
    @endforeach
</div>
@endsection
