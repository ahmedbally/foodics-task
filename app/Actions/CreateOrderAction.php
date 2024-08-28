<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class CreateOrderAction
{
    use AsAction;

    /**
     * @throws Throwable
     */
    public function handle(Collection $orderProducts): Order
    {
        return DB::transaction(function () use ($orderProducts) {
            $order = Order::create();

            $products = Product::whereIn('id', $orderProducts->pluck('product_id'))
                ->lockForUpdate()
                ->get();

            $orderProducts->each(function ($orderProduct) use ($order, $products) {
                $product = $products->firstWhere('id', $orderProduct['product_id']);

                ProcessProductAction::run($product, $orderProduct['quantity']);

                $order->products()->attach($product->id, [
                    'quantity' => $orderProduct['quantity'],
                ]);
            });

            return $order;
        });
    }
}
