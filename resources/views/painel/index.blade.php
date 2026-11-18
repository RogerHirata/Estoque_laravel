@extends('layouts.app')

@section('heading', 'Painel')
@section('subheading', 'Resumo do estoque, clientes e pedidos.')

@section('content')
@php
    $cards = [
        ['label' => 'Produtos', 'value' => $stats['products'], 'hint' => 'cadastrados', 'href' => route('products.index'), 'tone' => 'stat-products', 'glyph' => 'box'],
        ['label' => 'Categorias', 'value' => $stats['categories'], 'hint' => 'no catálogo', 'href' => route('categories.index'), 'tone' => 'stat-categories', 'glyph' => 'folder'],
        ['label' => 'Clientes', 'value' => $stats['customers'], 'hint' => 'cadastrados', 'href' => route('customers.index'), 'tone' => 'stat-customers', 'glyph' => 'users'],
        ['label' => 'Pedidos', 'value' => $stats['orders'], 'hint' => 'registrados', 'href' => route('orders.index'), 'tone' => 'stat-orders', 'glyph' => 'receipt'],
        ['label' => 'Faturamento', 'value' => 'R$ '.number_format($stats['revenue'], 2, ',', '.'), 'hint' => 'exceto cancelados', 'href' => route('reports.orders'), 'tone' => 'stat-revenue', 'glyph' => 'cash'],
        ['label' => 'Unidades em estoque', 'value' => $stats['units'], 'hint' => 'soma das quantidades', 'href' => route('reports.products'), 'tone' => 'stat-units', 'glyph' => 'layers'],
        ['label' => 'Valor em estoque', 'value' => 'R$ '.number_format($stats['stockValue'], 2, ',', '.'), 'hint' => 'preço × quantidade', 'href' => route('reports.products'), 'tone' => 'stat-value', 'glyph' => 'tag'],
        ['label' => 'Estoque baixo', 'value' => $stats['lowStock'], 'hint' => 'até '.$lowStockLimit.' un.', 'href' => route('reports.low-stock'), 'tone' => 'stat-low', 'glyph' => 'alert'],
    ];
@endphp

<div class="space-y-6">
    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <a href="{{ $card['href'] }}" class="stat-card {{ $card['tone'] }}">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-medium">{{ $card['label'] }}</p>
                    <span class="stat-icon">
                        @switch($card['glyph'])
                            @case('box')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7.5 12 3l9 4.5-9 4.5L3 7.5Z"/><path d="M3 7.5V16.5L12 21l9-4.5V7.5"/><path d="M12 12v9"/></svg>
                                @break
                            @case('folder')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7.5A1.5 1.5 0 0 1 4.5 6H9l2 2h8.5A1.5 1.5 0 0 1 21 9.5v8A1.5 1.5 0 0 1 19.5 19h-15A1.5 1.5 0 0 1 3 17.5v-10Z"/></svg>
                                @break
                            @case('users')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 20v-1.5A3.5 3.5 0 0 0 12.5 15h-5A3.5 3.5 0 0 0 4 18.5V20"/><circle cx="10" cy="8" r="3"/><path d="M20 20v-1.5A3.5 3.5 0 0 0 17 15"/><path d="M16 5.1a3 3 0 0 1 0 5.8"/></svg>
                                @break
                            @case('receipt')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3.5h12v17l-2-1.2-2 1.2-2-1.2-2 1.2-2-1.2-2 1.2V3.5Z"/><path d="M9 8h6M9 12h6"/></svg>
                                @break
                            @case('cash')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="12" cy="12" r="2.2"/><path d="M6 9.5h.01M18 14.5h.01"/></svg>
                                @break
                            @case('layers')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 12 9 5 9-5"/><path d="m3 16.5 9 5 9-5"/></svg>
                                @break
                            @case('tag')
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3H4.5A1.5 1.5 0 0 0 3 4.5V12l9.5 9.5 8-8L12 3Z"/><circle cx="7.5" cy="7.5" r="1"/></svg>
                                @break
                            @default
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 4 3.5 18.5h17L12 4Z"/><path d="M12 10v4"/><path d="M12 16.5h.01"/></svg>
                        @endswitch
                    </span>
                </div>
                <p class="mt-3 text-2xl font-semibold tracking-tight">{{ $card['value'] }}</p>
                <p class="stat-hint mt-1 text-xs">{{ $card['hint'] }}</p>
            </a>
        @endforeach
    </section>

    <section class="grid gap-6 xl:grid-cols-5">
        <div class="panel tone-green xl:col-span-3">
            <div class="panel-head panel-head--green">
                <div>
                    <h2 class="text-sm font-semibold">Faturamento por pedido</h2>
                    <p class="mt-1 text-xs">Valores reais dos pedidos que não estão cancelados.</p>
                </div>
                <a href="{{ route('reports.orders') }}" class="text-sm">Relatório</a>
            </div>
            <div class="p-5">

            @if ($chartOrders->isEmpty())
                <p class="px-2 py-16 text-center text-sm text-slate-500">Nenhum pedido para montar o gráfico.</p>
            @else
                <div class="flex items-end gap-4 overflow-x-auto pb-1">
                    @foreach ($chartOrders as $order)
                        @php $height = max(10, round(((float) $order->valor_total / $chartMax) * 100)); @endphp
                        <div class="flex w-16 shrink-0 flex-col items-center">
                            <p class="mb-2 text-center text-[11px] font-medium text-slate-600">R$ {{ number_format($order->valor_total, 0, ',', '.') }}</p>
                            <div class="flex h-40 w-full items-end">
                                <div class="w-full rounded-t-lg bg-gradient-to-t from-blue-600 to-violet-400" style="height: {{ $height }}%" title="Pedido #{{ $order->id }}"></div>
                            </div>
                            <p class="mt-2 text-xs font-semibold text-slate-700">#{{ $order->id }}</p>
                            <p class="text-[11px] text-slate-400">{{ $order->created_at?->format('d/m') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            </div>
        </div>

        <div class="panel tone-orange xl:col-span-2">
            <div class="panel-head panel-head--orange">
                <h2 class="text-sm font-semibold">Atenção no estoque</h2>
                <a href="{{ route('reports.low-stock') }}" class="text-sm">Relatório</a>
            </div>
            <div class="panel-list">
                @forelse ($lowStockProducts as $product)
                    <div class="flex items-center justify-between gap-4 px-5 py-3 text-sm">
                        <div>
                            <p class="font-medium text-slate-900">{{ $product->name }}</p>
                            <p class="text-slate-500">{{ $product->category?->name ?? 'Sem categoria' }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->estoque > 0 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800' }}">{{ $product->estoque }} un.</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-slate-500">Nenhum produto com estoque igual ou abaixo de {{ $lowStockLimit }}.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="panel tone-purple">
        <div class="panel-head panel-head--violet">
            <h2 class="text-sm font-semibold">Pedidos recentes</h2>
            <a href="{{ route('orders.index') }}" class="text-sm">Ver todos</a>
        </div>
        <div class="panel-list">
            @forelse ($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between gap-4 px-5 py-3 text-sm hover:bg-indigo-50/50">
                    <div>
                        <p class="font-medium text-slate-900">#{{ $order->id }} · {{ $order->customer?->name ?? 'Cliente removido' }}</p>
                        <p class="text-slate-500">{{ $order->created_at?->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-slate-900">R$ {{ number_format($order->valor_total, 2, ',', '.') }}</p>
                        @include('partials.status-badge', ['status' => $order->status])
                    </div>
                </a>
            @empty
                <p class="px-5 py-8 text-center text-sm text-slate-500">Nenhum pedido registrado.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
