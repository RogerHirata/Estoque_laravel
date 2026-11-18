<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Category::orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Informe o nome da categoria.',
            'name.max' => 'O nome pode ter no máximo 255 caracteres.',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Informe o nome da categoria.',
            'name.max' => 'O nome pode ter no máximo 255 caracteres.',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir esta categoria porque ela possui produtos.',
            ], 409);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
