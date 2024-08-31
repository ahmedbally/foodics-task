<?php

namespace App\Http\Controllers\API;

use App\Actions\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;

class CreateOrder extends Controller
{
    public function __invoke(OrderRequest $request)
    {
        /** @var Order $order */
        $order = CreateOrderAction::run(collect($request->validated('products')));

        $order->load('products');

        return OrderResource::make($order)->response();
    }
}
