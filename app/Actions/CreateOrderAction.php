<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Contracts\ProcessProductAction;
use Throwable;

class CreateOrderAction
{
    use AsAction;

    public function __construct(
        private readonly ProcessProductAction $processProductAction
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Collection $orderProducts): Order
    {
        $orderProducts = $this->prepareOrderProducts($orderProducts);

        return DB::transaction(function () use ($orderProducts) {
            $order = Order::create();

            $products = $this->getProducts($orderProducts);

            $this->attachProductsToOrder($order, $orderProducts, $products);

            return $order;
        });
    }

    private function prepareOrderProducts(Collection $orderProducts): Collection
    {
        return $orderProducts->groupBy('product_id')->map(fn($group, $productId) => [
            'product_id' => $productId,
            'quantity' => $group->sum('quantity'),
        ])->values();
    }

    private function getProducts(Collection $orderProducts): Collection
    {
        return Product::whereIn('id', $orderProducts->pluck('product_id'))
            ->lockForUpdate()
            ->get();
    }

    private function attachProductsToOrder(Order $order, Collection $orderProducts, Collection $products): void
    {
        $orderProducts->each(function ($orderProduct) use ($order, $products) {
            $product = $products->firstWhere('id', $orderProduct['product_id']);

            $this->processProductAction->run($product, $orderProduct['quantity']);

            $order->products()->attach($product->id, [
                'quantity' => $orderProduct['quantity'],
            ]);
        });
    }
}
