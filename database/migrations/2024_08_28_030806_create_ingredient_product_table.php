<?php

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ingredient_product', function (Blueprint $table) {
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Ingredient::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->float('quantity');
            $table->primary(['product_id', 'ingredient_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ingredient_product');
    }
};
