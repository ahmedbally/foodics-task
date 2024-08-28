<?php

namespace App\Actions;

use App\Exceptions\IngredientNotEnoughStockException;
use App\Models\Ingredient;
use App\Models\Product;
use Lorisleiva\Actions\Concerns\AsAction;

class ProcessProductAction
{
    use AsAction;

    public function handle(Product $product, int $quantity): bool
    {
        $product->ingredients()
            ->lockForUpdate()
            ->each(function (Ingredient $ingredient) use ($quantity) {
                if ($ingredient->stock < $ingredient->pivot->quantity * $quantity) {
                    throw new IngredientNotEnoughStockException('Ingredient stock not enough');
                }

                $ingredient->decrement('stock', $ingredient->pivot->quantity * $quantity);
            });

        return true;
    }
}
