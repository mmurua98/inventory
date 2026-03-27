<?php

namespace App\Http\Requests\Warehouse;

use App\Models\Warehouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends FormRequest
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
        /** @var Warehouse $warehouse */
        $warehouse = $this->route('warehouse');

        return [
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('warehouses', 'code')->ignore($warehouse->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'El código del almacén es obligatorio.',
            'code.string' => 'El código del almacén debe ser un texto.',
            'code.max' => 'El código del almacén no debe exceder 255 caracteres.',
            'code.unique' => 'El código del almacén ya existe.',
            'name.required' => 'El nombre del almacén es obligatorio.',
            'name.string' => 'El nombre del almacén debe ser un texto.',
            'name.max' => 'El nombre del almacén no debe exceder 255 caracteres.',
            'location.string' => 'La ubicación del almacén debe ser un texto.',
            'location.max' => 'La ubicación del almacén no debe exceder 255 caracteres.',
            'is_active.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }
}
