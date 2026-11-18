<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'descricao' => 'nullable|string|max:1000',
        ], $this->messages());

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category')->findOrFail($id);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'descricao' => 'nullable|string|max:1000',
        ], $this->messages());

        $product = Product::findOrFail($id);
        $product->update($validated);

        return redirect()->route('products.show', $product)->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Não é possível excluir este produto porque ele está em pedidos.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso.');
    }

    private function messages(): array
    {
        return [
            'category_id.required' => 'Selecione a categoria.',
            'category_id.exists' => 'Categoria não encontrada.',
            'name.required' => 'Informe o nome do produto.',
            'name.max' => 'O nome pode ter no máximo 255 caracteres.',
            'preco.required' => 'Informe o preço.',
            'preco.numeric' => 'O preço deve ser numérico.',
            'preco.min' => 'O preço não pode ser negativo.',
            'estoque.required' => 'Informe o estoque.',
            'estoque.integer' => 'O estoque deve ser um número inteiro.',
            'estoque.min' => 'O estoque não pode ser negativo.',
            'descricao.max' => 'A descrição pode ter no máximo 1000 caracteres.',
        ];
    }
}
