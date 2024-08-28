<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function withProducts(int $count = 3): static
    {
        return $this->afterCreating(function (Order $order) use ($count) {
            $order->products()->sync(
                Product::inRandomOrder()
                    ->limit($count)
                    ->pluck('id')
                    ->mapWithKeys(
                        fn ($id) => [
                            $id => [
                                'quantity' => $this->faker->randomNumber(1, true)
                            ]
                        ]
                    )
                    ->toArray()
            );
        });
    }
}
