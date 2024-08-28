<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $product = Product::factory()->create([
            'name' => 'Burger',
        ]);
        $ingredient1 = Ingredient::factory()->create([
            'name' => 'Beef',
            'stock' => 20000,
            'initial_stock' => 20000
        ]); // 20kg Beef
        $ingredient2 = Ingredient::factory()->create([
            'name' => 'Cheese',
            'stock' => 5000,
            'initial_stock' => 5000
        ]);  // 5kg Cheese
        $ingredient3 = Ingredient::factory()->create([
            'name' => 'Onion',
            'stock' => 1000,
            'initial_stock' => 1000
        ]);  // 1kg Onion

        $product->ingredients()->attach([
            $ingredient1->id => ['quantity' => 150], // 150g Beef
            $ingredient2->id => ['quantity' => 30],  // 30g Cheese
            $ingredient3->id => ['quantity' => 20],  // 20g Onion
        ]);

    }
}
