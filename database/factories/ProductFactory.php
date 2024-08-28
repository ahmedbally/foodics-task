<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function withIngredients(int $count = 3): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $product->ingredients()->sync(
                Ingredient::inRandomOrder()
                    ->limit($count)
                    ->pluck('id')
                    ->mapWithKeys(
                        fn ($id) => [
                            $id => [
                                'quantity' => $this->faker->randomNumber(3)
                            ]
                        ]
                    )
                    ->toArray()
            );
        });
    }
}
