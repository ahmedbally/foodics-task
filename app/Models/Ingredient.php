<?php

namespace App\Models;

use App\Observers\IngredientObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([IngredientObserver::class])]
class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'initial_stock',
        'alert_sent',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function isLowStock(): bool
    {
        return $this->stock / $this->initial_stock < stock_min_percentage();
    }
}
