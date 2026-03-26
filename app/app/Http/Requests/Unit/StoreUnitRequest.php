<?php

namespace App\Http\Requests\Unit;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:255', 'unique:units,code'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'El código de la unidad es obligatorio.',
            'code.string' => 'El código de la unidad debe ser un texto.',
            'code.max' => 'El código de la unidad no debe exceder 255 caracteres.',
            'code.unique' => 'El código de la unidad ya existe.',
            'name.required' => 'El nombre de la unidad es obligatorio.',
            'name.string' => 'El nombre de la unidad debe ser un texto.',
            'name.max' => 'El nombre de la unidad no debe exceder 255 caracteres.',
        ];
    }
}
