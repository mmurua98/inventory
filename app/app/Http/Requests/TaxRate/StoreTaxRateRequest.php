<?php

namespace App\Http\Requests\TaxRate;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxRateRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:255', 'unique:tax_rates,code'],
            'name' => ['required', 'string', 'max:255'],
            'rate_percent' => ['required', 'numeric', 'between:0,999.99'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'El código del impuesto es obligatorio.',
            'code.string' => 'El código del impuesto debe ser un texto.',
            'code.max' => 'El código del impuesto no debe exceder 255 caracteres.',
            'code.unique' => 'El código del impuesto ya existe.',
            'name.required' => 'El nombre del impuesto es obligatorio.',
            'name.string' => 'El nombre del impuesto debe ser un texto.',
            'name.max' => 'El nombre del impuesto no debe exceder 255 caracteres.',
            'rate_percent.required' => 'La tasa de impuesto es obligatoria.',
            'rate_percent.numeric' => 'La tasa de impuesto debe ser numérica.',
            'rate_percent.between' => 'La tasa de impuesto debe estar entre 0 y 999.99.',
            'is_active.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }
}
