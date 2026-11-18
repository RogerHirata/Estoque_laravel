<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $lowStockLimit = 5;
        $chartOrders = Order::query()
            ->where('status', '!=', 'cancelado')
            ->latest('id')
            ->take(8)
            ->get(['id', 'valor_total', 'created_at'])
            ->sortBy('id')
            ->values();

        return view('painel.index', [
            'chartOrders' => $chartOrders,
            'chartMax' => max(1, (float) $chartOrders->max('valor_total')),
            'stats' => [
                'products' => Product::count(),
                'categories' => Category::count(),
                'customers' => Customer::count(),
                'orders' => Order::count(),
                'revenue' => (float) Order::where('status', '!=', 'cancelado')->sum('valor_total'),
                'units' => (int) Product::sum('estoque'),
                'stockValue' => (float) (Product::query()->selectRaw('COALESCE(SUM(preco * estoque), 0) as total')->value('total') ?? 0),
                'lowStock' => Product::where('estoque', '<=', $lowStockLimit)->count(),
            ],
            'lowStockLimit' => $lowStockLimit,
            'recentOrders' => Order::with('customer')->latest()->take(5)->get(),
            'lowStockProducts' => Product::with('category')
                ->where('estoque', '<=', $lowStockLimit)
                ->orderBy('estoque')
                ->take(5)
                ->get(),
        ]);
    }
}
