<?php

namespace App\Actions;

use App\Contracts\ProcessProductAction;
use App\Exceptions\IngredientNotEnoughStockException;
use App\Models\Ingredient;
use App\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class DefaultProcessProductAction implements ProcessProductAction
{
    use AsAction;

    public function handle(Product $product, int $quantity): bool
    {
        $product->ingredients()
            ->lockForUpdate()
            ->each(function (Ingredient $ingredient) use ($product, $quantity) {
               $this->checkIngredientStock($product, $ingredient, $quantity);
            });

        return true;
    }

    private function checkIngredientStock(Product $product,Ingredient $ingredient, int $quantity): void
    {
        if ($ingredient->stock < $ingredient->pivot->quantity * $quantity) {
            throw new IngredientNotEnoughStockException(
                "Product {$product->name} can't be processed because ingredient {$ingredient->name} stock not enough"
            );
        }

        $ingredient->decrement('stock', $ingredient->pivot->quantity * $quantity);
    }
}
