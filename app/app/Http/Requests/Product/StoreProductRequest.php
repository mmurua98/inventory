<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        return [
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'integer', 'exists:product_categories,id'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'tax_rate_id' => ['required', 'integer', 'exists:tax_rates,id'],
            'min_stock' => ['required', 'numeric', 'min:0'],
            'max_stock' => ['nullable', 'numeric', 'gte:min_stock'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'sku.required' => 'El SKU del producto es obligatorio.',
            'sku.string' => 'El SKU del producto debe ser un texto.',
            'sku.max' => 'El SKU del producto no debe exceder 255 caracteres.',
            'sku.unique' => 'El SKU del producto ya existe.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.string' => 'El nombre del producto debe ser un texto.',
            'name.max' => 'El nombre del producto no debe exceder 255 caracteres.',
            'description.string' => 'La descripción del producto debe ser un texto.',
            'category_id.required' => 'La categoría del producto es obligatoria.',
            'category_id.integer' => 'La categoría del producto debe ser un número entero.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'unit_id.required' => 'La unidad del producto es obligatoria.',
            'unit_id.integer' => 'La unidad del producto debe ser un número entero.',
            'unit_id.exists' => 'La unidad seleccionada no existe.',
            'tax_rate_id.required' => 'La tasa de impuesto del producto es obligatoria.',
            'tax_rate_id.integer' => 'La tasa de impuesto del producto debe ser un número entero.',
            'tax_rate_id.exists' => 'La tasa de impuesto seleccionada no existe.',
            'min_stock.required' => 'El stock mínimo es obligatorio.',
            'min_stock.numeric' => 'El stock mínimo debe ser numérico.',
            'min_stock.min' => 'El stock mínimo no puede ser negativo.',
            'max_stock.numeric' => 'El stock máximo debe ser numérico.',
            'max_stock.gte' => 'El stock máximo debe ser mayor o igual al stock mínimo.',
            'is_active.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }
}
