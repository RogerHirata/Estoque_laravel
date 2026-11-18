<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('estoque', '>', 0)->orderBy('name')->get();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ], [
            'customer_id.required' => 'Selecione o cliente.',
            'customer_id.exists' => 'Cliente não encontrado.',
            'product_id.required' => 'Selecione o produto.',
            'product_id.exists' => 'Produto não encontrado.',
            'quantity.required' => 'Informe a quantidade.',
            'quantity.integer' => 'A quantidade deve ser um número inteiro.',
            'quantity.min' => 'A quantidade mínima é 1.',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->estoque < $validated['quantity']) {
            return back()
                ->withErrors(['quantity' => 'Quantidade em estoque insuficiente. Disponível: '.$product->estoque.'.'])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $product) {
            $product = Product::query()->lockForUpdate()->findOrFail($product->id);

            if ($product->estoque < $validated['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => 'Quantidade em estoque insuficiente. Disponível: '.$product->estoque.'.',
                ]);
            }

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'valor_total' => round((float) $product->preco * $validated['quantity'], 2),
                'status' => 'completed',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantidade' => $validated['quantity'],
                'preco_unitario' => $product->preco,
            ]);

            $product->decrement('estoque', $validated['quantity']);
        });

        return redirect()->route('orders.index')->with('success', 'Pedido realizado com sucesso.');
    }

    public function show(string $id)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function edit(string $id)
    {
        $order = Order::with(['customer', 'items.product'])->findOrFail($id);
        $customers = Customer::orderBy('name')->get();

        return view('orders.edit', compact('order', 'customers'));
    }

    public function update(Request $request, string $id)
    {
        $order = Order::with('items')->findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'status' => 'required|in:'.implode(',', array_keys(Order::STATUSES)),
            'quantity' => 'required|integer|min:1',
        ], [
            'customer_id.required' => 'Selecione o cliente.',
            'status.required' => 'Selecione o status.',
            'status.in' => 'Status inválido.',
            'quantity.required' => 'Informe a quantidade.',
            'quantity.min' => 'A quantidade mínima é 1.',
        ]);

        $order->syncReservation(
            (int) $validated['customer_id'],
            $validated['status'],
            $order->items->count() === 1 ? (int) $validated['quantity'] : null,
        );

        return redirect()->route('orders.show', $order)->with('success', 'Pedido atualizado com sucesso.');
    }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->deleteReturningStock();

        return redirect()->route('orders.index')->with('success', 'Pedido excluído e estoque devolvido.');
    }
}
