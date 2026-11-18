<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function products(Request $request): View
    {
        return view('reports.products', $this->productData($request));
    }

    public function productsPdf(Request $request): Response
    {
        $data = $this->productData($request);

        return $this->pdf(
            'Relatório de produtos',
            [
                ['label' => 'Produto'],
                ['label' => 'Categoria'],
                ['label' => 'Preço', 'align' => 'right'],
                ['label' => 'Estoque', 'align' => 'right'],
            ],
            $data['products']->map(fn (Product $product) => [
                $product->name,
                $product->category?->name ?? '—',
                'R$ '.number_format((float) $product->preco, 2, ',', '.'),
                (string) $product->estoque,
            ])->all(),
            $data['generatedAt'],
            $data['summary'],
        );
    }

    public function customers(Request $request): View
    {
        return view('reports.customers', $this->customerData($request));
    }

    public function customersPdf(Request $request): Response
    {
        $data = $this->customerData($request);

        return $this->pdf(
            'Relatório de clientes',
            [
                ['label' => 'Nome'],
                ['label' => 'E-mail'],
                ['label' => 'Telefone'],
                ['label' => 'Cadastro'],
            ],
            $data['customers']->map(fn (Customer $customer) => [
                $customer->name,
                $customer->email,
                $customer->phone ?: '—',
                $customer->created_at?->format('d/m/Y') ?? '—',
            ])->all(),
            $data['generatedAt'],
            $data['summary'],
        );
    }

    public function orders(Request $request): View
    {
        return view('reports.orders', $this->orderData($request));
    }

    public function ordersPdf(Request $request): Response
    {
        $data = $this->orderData($request);

        return $this->pdf(
            'Relatório de pedidos',
            [
                ['label' => 'Pedido'],
                ['label' => 'Cliente'],
                ['label' => 'Status'],
                ['label' => 'Data'],
                ['label' => 'Total', 'align' => 'right'],
            ],
            $data['orders']->map(fn (Order $order) => [
                '#'.$order->id,
                $order->customer?->name ?? 'Cliente removido',
                Order::label($order->status),
                $order->created_at?->format('d/m/Y H:i') ?? '—',
                'R$ '.number_format((float) $order->valor_total, 2, ',', '.'),
            ])->all(),
            $data['generatedAt'],
            $data['summary'],
        );
    }

    public function lowStock(Request $request): View
    {
        return view('reports.low-stock', $this->stockData($request));
    }

    public function lowStockPdf(Request $request): Response
    {
        $data = $this->stockData($request);

        return $this->pdf(
            'Relatório de estoque',
            [
                ['label' => 'Produto'],
                ['label' => 'Categoria'],
                ['label' => 'Estoque', 'align' => 'right'],
            ],
            $data['products']->map(fn (Product $product) => [
                $product->name,
                $product->category?->name ?? '—',
                (string) $product->estoque,
            ])->all(),
            $data['generatedAt'],
            $data['summary'],
        );
    }

    private function productData(Request $request): array
    {
        $filters = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $products = Product::with('category')
            ->when($filters['category_id'] ?? null, fn (Builder $query, $categoryId) => $query->where('category_id', $categoryId))
            ->orderBy('name')
            ->get();

        $category = isset($filters['category_id'])
            ? Category::find($filters['category_id'])
            : null;

        return [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'categoryId' => $filters['category_id'] ?? null,
            'generatedAt' => now(),
            'summary' => $category ? 'Categoria: '.$category->name : 'Todas as categorias',
        ];
    }

    private function customerData(Request $request): array
    {
        $filters = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ], [
            'from.date' => 'A data inicial é inválida.',
            'to.date' => 'A data final é inválida.',
            'to.after_or_equal' => 'A data final deve ser igual ou posterior à inicial.',
        ]);

        $customers = $this->between(Customer::query(), $filters)
            ->orderBy('name')
            ->get();

        return [
            'customers' => $customers,
            'from' => $filters['from'] ?? null,
            'to' => $filters['to'] ?? null,
            'generatedAt' => now(),
            'summary' => $this->periodSummary($filters['from'] ?? null, $filters['to'] ?? null),
        ];
    }

    private function orderData(Request $request): array
    {
        $filters = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'status' => 'nullable|in:'.implode(',', array_keys(Order::STATUSES)),
        ], [
            'from.date' => 'A data inicial é inválida.',
            'to.date' => 'A data final é inválida.',
            'to.after_or_equal' => 'A data final deve ser igual ou posterior à inicial.',
            'status.in' => 'Status inválido.',
        ]);

        $orders = $this->between(Order::with(['customer', 'items.product']), $filters)
            ->when($filters['status'] ?? null, fn (Builder $query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        $status = $filters['status'] ?? null;

        return [
            'orders' => $orders,
            'totalGeral' => $orders->where('status', '!=', 'cancelado')->sum('valor_total'),
            'from' => $filters['from'] ?? null,
            'to' => $filters['to'] ?? null,
            'status' => $status,
            'generatedAt' => now(),
            'summary' => trim($this->periodSummary($filters['from'] ?? null, $filters['to'] ?? null).($status ? ' · Status: '.Order::label($status) : ' · Todos os status')),
        ];
    }

    private function stockData(Request $request): array
    {
        $filters = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'minimum' => 'nullable|integer|min:0',
        ], [
            'minimum.integer' => 'O limite de estoque deve ser um número inteiro.',
            'minimum.min' => 'O limite não pode ser negativo.',
        ]);

        $minimum = array_key_exists('minimum', $filters) && $filters['minimum'] !== null
            ? (int) $filters['minimum']
            : 5;

        $products = Product::with('category')
            ->where('estoque', '<=', $minimum)
            ->when($filters['category_id'] ?? null, fn (Builder $query, $categoryId) => $query->where('category_id', $categoryId))
            ->orderBy('estoque')
            ->orderBy('name')
            ->get();

        $category = isset($filters['category_id']) ? Category::find($filters['category_id']) : null;

        return [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'categoryId' => $filters['category_id'] ?? null,
            'minimum' => $minimum,
            'generatedAt' => now(),
            'summary' => 'Estoque até '.$minimum.($category ? ' · Categoria: '.$category->name : ''),
        ];
    }

    private function between(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['from'] ?? null, fn (Builder $builder, $from) => $builder->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn (Builder $builder, $to) => $builder->whereDate('created_at', '<=', $to));
    }

    private function periodSummary(?string $from, ?string $to): string
    {
        if ($from && $to) {
            return 'Período: '.date('d/m/Y', strtotime($from)).' a '.date('d/m/Y', strtotime($to));
        }

        if ($from) {
            return 'A partir de '.date('d/m/Y', strtotime($from));
        }

        if ($to) {
            return 'Até '.date('d/m/Y', strtotime($to));
        }

        return 'Todo o período';
    }

    private function pdf(string $title, array $columns, array $rows, $generatedAt, string $summary): Response
    {
        $html = view('reports.pdf', compact('title', 'columns', 'rows', 'generatedAt', 'summary'))->render();

        $dompdf = new Dompdf(['isRemoteEnabled' => false]);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = str($title)->slug().'.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
