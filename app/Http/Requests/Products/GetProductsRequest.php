<?php

namespace App\Http\Requests\Products;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }
}
