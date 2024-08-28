<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(10),
            'stock' => function (array $attributes) {
                return $attributes['initial_stock'] - $this->faker->randomFloat(2,min: 0, max: $attributes['initial_stock']);
            }, // in grams
            'initial_stock' => $this->faker->randomFloat(2, min: 100, max: 10000), // in grams
            'alert_sent' => function (array $attributes) {
                return $attributes['stock'] / $attributes['initial_stock'] < stock_min_percentage();
            },
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
