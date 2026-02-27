<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Must be true to allow the request to proceed
        return auth()->check();
    }

    public function rules(): array
    {
        // Find the product to check current stock
        $product = Product::find($this->product_id);
        $maxStock = $product ? $product->stock : 0;

        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                "max:$maxStock" // Ensures user doesn't buy more than available
            ],
            'shipping_address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.max' => 'Sorry, we do not have enough items in stock.',
        ];
    }
}
