<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Product::with('category')->orderBy('name')->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'descricao' => 'nullable|string',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load('category'), 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load('category'));
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'preco' => 'sometimes|required|numeric|min:0',
            'estoque' => 'sometimes|required|integer|min:0',
            'descricao' => 'nullable|string',
        ]);

        $product->update($validated);

        return response()->json($product->load('category'));
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->orderItems()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir este produto porque ele está em pedidos.',
            ], 409);
        }

        $product->delete();

        return response()->json(null, 204);
    }
}
