<?php

namespace App\Http\Requests\Supplier;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierRequest extends FormRequest
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
        /** @var Supplier $supplier */
        $supplier = $this->route('supplier');

        return [
            'supplier_code' => ['required', 'string', Rule::in([$supplier->supplier_code])],
            'legal_name' => ['required', 'string', 'max:255'],
            'trade_name' => ['nullable', 'string', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address_line' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'default_tax_rate_id' => ['nullable', 'integer', 'exists:tax_rates,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'supplier_code.required' => 'El código del proveedor es obligatorio.',
            'supplier_code.string' => 'El código del proveedor debe ser un texto.',
            'supplier_code.in' => 'El código del proveedor no se puede modificar.',
            'legal_name.required' => 'La razón social del proveedor es obligatoria.',
            'legal_name.string' => 'La razón social del proveedor debe ser un texto.',
            'legal_name.max' => 'La razón social del proveedor no debe exceder 255 caracteres.',
            'trade_name.string' => 'El nombre comercial debe ser un texto.',
            'trade_name.max' => 'El nombre comercial no debe exceder 255 caracteres.',
            'tax_id.string' => 'El RFC/NIF debe ser un texto.',
            'tax_id.max' => 'El RFC/NIF no debe exceder 255 caracteres.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no debe exceder 255 caracteres.',
            'phone.string' => 'El teléfono debe ser un texto.',
            'phone.max' => 'El teléfono no debe exceder 255 caracteres.',
            'address_line.string' => 'La dirección debe ser un texto.',
            'address_line.max' => 'La dirección no debe exceder 255 caracteres.',
            'city.string' => 'La ciudad debe ser un texto.',
            'city.max' => 'La ciudad no debe exceder 255 caracteres.',
            'state.string' => 'El estado debe ser un texto.',
            'state.max' => 'El estado no debe exceder 255 caracteres.',
            'country.string' => 'El país debe ser un texto.',
            'country.max' => 'El país no debe exceder 255 caracteres.',
            'postal_code.string' => 'El código postal debe ser un texto.',
            'postal_code.max' => 'El código postal no debe exceder 255 caracteres.',
            'default_tax_rate_id.integer' => 'La tasa de impuesto predeterminada debe ser un número entero.',
            'default_tax_rate_id.exists' => 'La tasa de impuesto predeterminada seleccionada no existe.',
            'is_active.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }
}
