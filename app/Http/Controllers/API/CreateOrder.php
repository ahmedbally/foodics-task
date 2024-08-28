<?php

namespace App\Http\Controllers\API;

use App\Actions\CreateOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;

class CreateOrder extends Controller
{
    public function __invoke(OrderRequest $request)
    {
        $order = CreateOrderAction::run(collect($request->validated('products')));

        return response()->json($order);
    }
}
