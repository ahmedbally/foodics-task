<?php

namespace Tests\Feature\Controllers;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    private Collection $products;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $ingredientsCount = 3;
        $productsCount = 2;
        Ingredient::factory()->count($ingredientsCount)->create();
        $this->products = Product::factory()->count($productsCount)->withIngredients($ingredientsCount)->create();
    }

    public function test_create_an_order_with_valid_product()
    {
        // assume that the stock is enough
        $this->products->each(function (Product $product) {
            $product->ingredients->each(function (Ingredient $ingredient) {
                $ingredient->update(['stock' => $ingredient->pivot->quantity * 2]);
            });
        });

        $firstProduct = $this->products->first();
        $secondProduct = $this->products->last();


        $response = $this->postJson('api/orders', [
            'products' => [
                [
                    'product_id' => $firstProduct->id,
                    'quantity' => 1,

                ],
                [
                    'product_id' => $secondProduct->id,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonCount(2, 'data.products')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'products' => [
                        '*' => [
                            'id',
                            'name',
                            'quantity',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_create_an_order_with_invalid_product_stock()
    {
        $product = $this->products->first();

        // assume that the stock is not enough
        $product->ingredients->each(function (Ingredient $ingredient) {
            $ingredient->update(['stock' => 0]);
        });

        $ingredient = $product->ingredients->first();

        $response = $this->postJson('api/orders', [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => "Product {$product->name} can't be processed because ingredient {$ingredient->name} stock not enough"
            ]);
    }

    public function test_create_an_order_with_invalid_product_id()
    {
        $response = $this->postJson('api/orders', [
            'products' => [
                [
                    'product_id' => 0,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'products.0.product_id'
            ]);
    }

    public function test_create_an_order_with_invalid_product_quantity()
    {
        $product = $this->products->first();

        $response = $this->postJson('api/orders', [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 0,
                ]
            ]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'products.0.quantity'
            ]);
    }
}
