<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // Don't forget to import this!

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $isCreating = $this->isMethod('post');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')->ignore($this->product?->id),
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',

            'images' => $isCreating ? 'required|array' : 'nullable|array',

            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a product name.',
            'name.unique' => 'This product name already exists.',
            'price.required' => 'Please set a price for this item.',
            'price.min' => 'Price cannot be a negative value.',
            'category_id.exists' => 'The selected category is invalid.',
            'images.required' => 'You must upload at least one image when creating a product.',
            'images.*.image' => 'One of your uploads is not a valid image file.',
            'images.*.max' => 'Images must not exceed 2MB in size.',
            'tags.*.exists' => 'One of the selected tags is invalid.',
        ];
    }
}
