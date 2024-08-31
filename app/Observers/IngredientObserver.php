<?php

namespace App\Observers;

use App\Mail\IngredientLowStockMail;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Mail;

class IngredientObserver
{
    public bool $afterCommit = true;

    public function updated(Ingredient $ingredient): void
    {
        $this->handleStock($ingredient);
    }

    private function handleStock(Ingredient $ingredient): void
    {
        if (
            $ingredient->wasChanged('stock') &&
            $ingredient->isLowStock() &&
            ! $ingredient->alert_sent
        ) {
            Mail::to(stock_notification_email())->send(new IngredientLowStockMail($ingredient));
            $ingredient->updateQuietly([
                'alert_sent' => true,
            ]);
        }
    }
}
