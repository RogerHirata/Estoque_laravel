<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryCompletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_pages_render_saved_records(): void
    {
        $category = Category::create(['name' => 'Alimentos']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Arroz',
            'preco' => 8.5,
            'estoque' => 4,
            'descricao' => 'Pacote 1kg',
        ]);
        $customer = Customer::create([
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'phone' => '11999999999',
        ]);

        $this->get(route('products.show', $product))->assertOk()->assertSee('Arroz')->assertSee('Pacote 1kg');
        $this->get(route('categories.show', $category))->assertOk()->assertSee('Arroz');
        $this->get(route('customers.show', $customer))->assertOk()->assertSee('ana@example.com');
    }

    public function test_cancelling_order_returns_stock_and_delete_does_not_double_return(): void
    {
        [$order, $product] = $this->orderWithStock(10, 3);

        $order->syncReservation($order->customer_id, 'cancelado', 3);

        $this->assertSame(10, $product->fresh()->estoque);
        $this->assertSame('cancelado', $order->fresh()->status);

        $order->fresh()->deleteReturningStock();

        $this->assertSame(10, $product->fresh()->estoque);
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_reports_export_pdf_from_database_records(): void
    {
        $category = Category::create(['name' => 'Limpeza']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Sabao',
            'preco' => 4,
            'estoque' => 2,
        ]);

        $response = $this->get(route('reports.products.pdf', ['category_id' => $category->id]));

        $response->assertOk();
        $this->assertStringStartsWith('application/pdf', (string) $response->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    private function orderWithStock(int $stock, int $quantity): array
    {
        $category = Category::create(['name' => 'Categoria '.$stock]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Produto '.$stock,
            'preco' => 10,
            'estoque' => $stock - $quantity,
        ]);
        $customer = Customer::create([
            'name' => 'Cliente '.$stock,
            'email' => 'cliente'.$stock.'@example.com',
        ]);
        $order = Order::create([
            'customer_id' => $customer->id,
            'valor_total' => 10 * $quantity,
            'status' => 'completed',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantidade' => $quantity,
            'preco_unitario' => 10,
        ]);

        return [$order, $product];
    }
}
