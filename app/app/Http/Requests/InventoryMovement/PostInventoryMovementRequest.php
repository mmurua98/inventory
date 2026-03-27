<?php

namespace App\Http\Requests\InventoryMovement;

use Illuminate\Foundation\Http\FormRequest;

class PostInventoryMovementRequest extends FormRequest
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
            'status' => ['required', 'in:POSTED'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'El estado es obligatorio para publicar el movimiento.',
            'status.in' => 'El estado debe ser POSTED para publicar el movimiento.',
            'notes.string' => 'Las notas deben ser un texto.',
        ];
    }
}
