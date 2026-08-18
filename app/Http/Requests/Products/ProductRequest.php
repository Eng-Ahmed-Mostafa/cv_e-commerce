<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            'slug' => 'required|string|max:255|unique:products,slug',
            'image' => 'nullable|mimes:png,jpg,jpeg|image|max:2048',
            'images.*' => 'nullable|mimes:png,jpg,jpeg|image|max:2048',
            'short_description' => "required|string|max:255",
            'description' => "required|string",
            'price' => "required",
            'sale_price' => "required",
            "SKU" => "required",
            "feature" => "required",
            "stock" => "required",
            "quantity" => "required",
            "category_id" => "required",
            "brand_id" => "required"
        ];
    }
}
