<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $category = $this->route('category'); // Get category from route

        // If we have a category, get its ID, otherwise it's null
        $categoryId = $category ? $category->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Only add the ignore ID part if $categoryId is NOT null
                $categoryId
                    ? "unique:categories,name,{$categoryId}"
                    : "unique:categories,name"
            ],
            'description' => 'nullable|string',
        ];
    }
}
