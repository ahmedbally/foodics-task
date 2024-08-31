<?php

namespace Tests\Unit\Actions;


use App\Actions\DefaultProcessProductAction;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DefaultProcessProductActionTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    public function setUp(): void
    {
        parent::setUp();

        $ingredient = Ingredient::factory()->create(['stock' => 1]);
        $this->product = Product::factory()->create();
        $this->product->ingredients()->attach($ingredient, ['quantity' => 1]);
    }
    public function test_process_product_if_enough_stock()
    {
        $result = DefaultProcessProductAction::run($this->product, 1);

        $this->assertTrue($result);
    }

    public function test_process_product_if_not_enough_stock()
    {
        $ingredient = $this->product->ingredients->first();
        $ingredient->update(['stock' => 0]);

        $this->expectExceptionMessage("Product {$this->product->name} can't be processed because ingredient {$ingredient->name} stock not enough");

        DefaultProcessProductAction::run($this->product, 1);
    }
}
