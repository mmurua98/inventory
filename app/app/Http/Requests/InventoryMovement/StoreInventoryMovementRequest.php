<?php

namespace App\Http\Requests\InventoryMovement;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMovementRequest extends FormRequest
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
            'movement_type_id' => ['required', 'integer', 'exists:movement_types,id'],
            'occurred_at' => ['required', 'date'],
            'warehouse_id' => ['required', 'integer', 'exists:warehouses,id'],
            'reference_type' => ['nullable', 'string', 'max:255'],
            'reference_id' => ['nullable', 'integer'],
            'status' => ['sometimes', 'in:DRAFT'],
            'notes' => ['nullable', 'string'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.line_no' => ['required', 'integer', 'min:1', 'distinct'],
            'lines.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'lines.*.qty' => ['required', 'numeric', 'gt:0'],
            'lines.*.unit_cost' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'movement_type_id.required' => 'El tipo de movimiento es obligatorio.',
            'movement_type_id.integer' => 'El tipo de movimiento debe ser un número entero.',
            'movement_type_id.exists' => 'El tipo de movimiento seleccionado no existe.',
            'occurred_at.required' => 'La fecha del movimiento es obligatoria.',
            'occurred_at.date' => 'La fecha del movimiento debe ser válida.',
            'warehouse_id.required' => 'El almacén es obligatorio.',
            'warehouse_id.integer' => 'El almacén debe ser un número entero.',
            'warehouse_id.exists' => 'El almacén seleccionado no existe.',
            'reference_type.string' => 'El tipo de referencia debe ser un texto.',
            'reference_type.max' => 'El tipo de referencia no debe exceder 255 caracteres.',
            'reference_id.integer' => 'La referencia debe ser un número entero.',
            'status.in' => 'El estado del movimiento debe ser DRAFT al crear.',
            'notes.string' => 'Las notas deben ser un texto.',
            'lines.required' => 'Debe agregar al menos una línea de movimiento.',
            'lines.array' => 'Las líneas del movimiento deben tener un formato válido.',
            'lines.min' => 'Debe agregar al menos una línea de movimiento.',
            'lines.*.line_no.required' => 'El número de línea es obligatorio.',
            'lines.*.line_no.integer' => 'El número de línea debe ser un número entero.',
            'lines.*.line_no.min' => 'El número de línea debe ser mayor a 0.',
            'lines.*.line_no.distinct' => 'El número de línea no puede repetirse.',
            'lines.*.product_id.required' => 'El producto es obligatorio en cada línea.',
            'lines.*.product_id.integer' => 'El producto debe ser un número entero.',
            'lines.*.product_id.exists' => 'El producto seleccionado no existe.',
            'lines.*.qty.required' => 'La cantidad es obligatoria en cada línea.',
            'lines.*.qty.numeric' => 'La cantidad debe ser numérica.',
            'lines.*.qty.gt' => 'La cantidad debe ser mayor a 0.',
            'lines.*.unit_cost.numeric' => 'El costo unitario debe ser numérico.',
            'lines.*.unit_cost.min' => 'El costo unitario no puede ser negativo.',
        ];
    }
}
