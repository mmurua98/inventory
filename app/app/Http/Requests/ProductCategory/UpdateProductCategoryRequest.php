<?php

namespace App\Http\Requests\ProductCategory;

use App\Models\ProductCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var ProductCategory $productCategory */
        $productCategory = $this->route('product_category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_categories', 'name')->ignore($productCategory->id),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.string' => 'El nombre de la categoría debe ser un texto.',
            'name.max' => 'El nombre de la categoría no debe exceder 255 caracteres.',
            'name.unique' => 'El nombre de la categoría ya existe.',
            'is_active.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }
}
