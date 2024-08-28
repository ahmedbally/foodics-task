<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->primary(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
