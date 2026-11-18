<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Order::with(['customer', 'items.product'])->latest()->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->estoque < $validated['quantity']) {
            return response()->json([
                'message' => 'Quantidade em estoque insuficiente.',
                'disponivel' => $product->estoque,
            ], 422);
        }

        $order = DB::transaction(function () use ($validated, $product) {
            $total = $product->preco * $validated['quantity'];

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'valor_total' => $total,
                'status' => 'completed',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantidade' => $validated['quantity'],
                'preco_unitario' => $product->preco,
            ]);

            $product->decrement('estoque', $validated['quantity']);

            return $order->load(['customer', 'items.product']);
        });

        return response()->json($order, 201);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json($order->load(['customer', 'items.product']));
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'sometimes|required|exists:customers,id',
            'status' => 'sometimes|required|in:'.implode(',', array_keys(Order::STATUSES)),
            'quantity' => 'sometimes|required|integer|min:1',
        ]);

        $order->syncReservation(
            (int) ($validated['customer_id'] ?? $order->customer_id),
            $validated['status'] ?? $order->status,
            array_key_exists('quantity', $validated) ? (int) $validated['quantity'] : null,
        );

        return response()->json($order->fresh()->load(['customer', 'items.product']));
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->deleteReturningStock();

        return response()->json(null, 204);
    }
}
