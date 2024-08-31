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
                    ->get()
                    ->mapWithKeys(
                        fn (Ingredient $ingredient) => [
                            $ingredient->id => [
                                'quantity' => $ingredient->initial_stock * 5 / 100
                            ]
                        ]
                    )
                    ->toArray()
            );
        });
    }
}
