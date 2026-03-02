<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'A review must be associated with a product.',
            'product_id.exists' => 'The selected product does not exist.',
            'rating.required' => 'Please provide a rating for this product.',
            'rating.integer' => 'Rating must be an integer value.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating cannot be greater than 5.',
        ];
    }
}
