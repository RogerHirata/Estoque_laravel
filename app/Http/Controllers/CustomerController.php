<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
        ], $this->messages());

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Customer::with('orders')->findOrFail($id);

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
        ], $this->messages());

        $customer->update($validated);

        return redirect()->route('customers.show', $customer)->with('success', 'Cliente atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->orders()->exists()) {
            return back()->with('error', 'Não é possível excluir este cliente porque ele possui pedidos.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Cliente excluído com sucesso.');
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Informe o nome do cliente.',
            'name.max' => 'O nome pode ter no máximo 255 caracteres.',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'phone.max' => 'O telefone pode ter no máximo 20 caracteres.',
        ];
    }
}
