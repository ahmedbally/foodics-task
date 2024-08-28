<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'products' => [
                'required',
                'array',
            ],
            'products.*.product_id' => [
                'required',
                'exists:products,id',
            ],
            'products.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
