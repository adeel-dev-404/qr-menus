<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string|max:1000',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'is_available'   => 'boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variant_names'           => 'nullable|array',
            'variant_names.*'         => 'nullable|string|max:100',
            'variant_prices'          => 'nullable|array',
            'variant_prices.*'        => 'nullable|numeric|min:0',
            'variant_discount_prices' => 'nullable|array',
            'variant_discount_prices.*' => 'nullable|numeric|min:0',
            'variant_available'       => 'nullable|array',
        ];
    }
}
