<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Customer::orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:customers,email,'.$customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return response()->json($customer);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        if ($customer->orders()->exists()) {
            return response()->json([
                'message' => 'Não é possível excluir este cliente porque ele possui pedidos.',
            ], 409);
        }

        $customer->delete();

        return response()->json(null, 204);
    }
}
