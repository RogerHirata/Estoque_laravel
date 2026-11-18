<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products_via_api(): void
    {
        $category = Category::create(['name' => 'Eletrônicos']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Mouse',
            'preco' => 49.9,
            'estoque' => 10,
        ]);

        $response = $this->getJson('/api/v1/products');

        $response->assertOk()
            ->assertJsonCount(1)
            ->assertJsonFragment(['name' => 'Mouse']);
    }
}
