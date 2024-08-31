<?php

namespace Actions;

use App\Actions\CreateOrderAction;
use App\Exceptions\IngredientNotEnoughStockException;
use App\Mail\IngredientLowStockMail;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CreateOrderActionTest extends TestCase
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

    public function test_create_an_order(): void
    {
        $product = $this->products->first();

        // assume that the stock is enough
        $product->ingredients->each(function (Ingredient $ingredient) {
            $ingredient->update(['stock' => $ingredient->pivot->quantity]);
        });

        $order = CreateOrderAction::run(collect([
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ]
        ]));

        $this->assertNotNull($order);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertCount(1, $order->products);
        $this->assertEquals(1, $order->products()->first()->pivot->quantity);
    }

    public function test_create_an_order_with_repeated_product()
    {
        $product = $this->products->first();

        // assume that the stock is enough
        $product->ingredients->each(function (Ingredient $ingredient) {
            $ingredient->update(['stock' => $ingredient->pivot->quantity * 2]);
        });

        $order = CreateOrderAction::run(collect([
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ]));

        $this->assertNotNull($order);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertCount(1, $order->products);
        $this->assertEquals(2, $order->products()->first()->pivot->quantity);
    }

    public function test_create_an_order_with_multiple_products()
    {
        // assume that the stock is enough
        $this->products->each(function (Product $product) {
            $product->ingredients->each(function (Ingredient $ingredient) {
                $ingredient->update(['stock' => $ingredient->pivot->quantity * 2]);
            });
        });

        $firstProduct = $this->products->first();
        $secondProduct = $this->products->last();

        $order = CreateOrderAction::run(collect([
            [
                'product_id' => $firstProduct->id,
                'quantity' => 1,
            ],
            [
                'product_id' => $secondProduct->id,
                'quantity' => 1,
            ],
        ]));

        $this->assertNotNull($order);
        $this->assertInstanceOf(Order::class, $order);
        $this->assertCount(2, $order->products);
        $this->assertEquals(1, $order->products->first()->pivot->quantity);
        $this->assertEquals(1, $order->products->last()->pivot->quantity);
    }
    public function test_create_an_order_with_not_enough_stock(): void
    {
        $product = $this->products->first();

        // assume that the stock is not enough
        $product->ingredients->each(function (Ingredient $ingredient) {
            $ingredient->update(['stock' => 0]);
        });

        $this->expectException(IngredientNotEnoughStockException::class);

        CreateOrderAction::run(collect([
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ],
        ]));
    }

    public function test_create_an_order_with_not_enough_stock_and_send_email(): void
    {
        $product = $this->products->first();

        // assume that the stock is going to be low
        $product->ingredients->each(function (Ingredient $ingredient) {
            $ingredient->update([
                'stock' => $ingredient->initial_stock / 2,
                'alert_sent' => false,
            ]);
        });

        CreateOrderAction::run(collect([
            [
                'product_id' => $product->id,
                'quantity' => 1,
            ]
        ]));

        Mail::assertQueued(IngredientLowStockMail::class, function ($mail) use ($product) {
            return $mail->ingredient->is($product->ingredients->first());
        });
        Mail::assertQueuedCount(3);
    }
}
