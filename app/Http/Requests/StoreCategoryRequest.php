<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'status'     => 'boolean',
            'sort_order' => 'integer|min:0',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
}