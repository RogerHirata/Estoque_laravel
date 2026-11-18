<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], $this->messages());

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Categoria cadastrada com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with('products')->findOrFail($id);

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ], $this->messages());

        $category = Category::findOrFail($id);
        $category->update($validated);

        return redirect()->route('categories.show', $category)->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Busca a categoria pelo ID e apaga do banco de dados
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
            return back()->with('error', 'Não é possível excluir esta categoria porque ela possui produtos.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso.');
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da categoria.',
            'name.max' => 'O nome pode ter no máximo 255 caracteres.',
        ];
    }
}
