<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSearchRequest extends FormRequest
{
    //! Autorizar busqueda de empleados
    public function authorize(): bool
    {
        return true;
    }

    //! Validar filtros de busqueda
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:150'],
            'area_id' => ['nullable', 'exists:areas,id'],
            'position_id' => ['nullable', 'exists:positions,id'],
            'location_id' => ['nullable', 'exists:locations,id'],
            'start_date_from' => ['nullable', 'date'],
            'start_date_to' => ['nullable', 'date', 'after_or_equal:start_date_from'],
            'sort_by' => ['nullable', 'in:full_name,dni,email'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
        ];
    }

    //! Mostrar mensajes de validacion
    public function messages(): array
    {
        return [
            'search.string' => 'La busqueda debe ser un texto valido.',
            'search.max' => 'La busqueda no puede superar los 150 caracteres.',
            'area_id.exists' => 'El area seleccionada no es valida.',
            'position_id.exists' => 'El cargo seleccionado no es valido.',
            'location_id.exists' => 'El local seleccionado no es valido.',
            'start_date_from.date' => 'La fecha inicial debe ser valida.',
            'start_date_to.date' => 'La fecha final debe ser valida.',
            'start_date_to.after_or_equal' => 'La fecha final no puede ser anterior a la fecha inicial.',
            'sort_by.in' => 'El campo de ordenamiento seleccionado no es valido.',
            'sort_direction.in' => 'La direccion de ordenamiento seleccionada no es valida.',
        ];
    }

    //! Mostrar nombres de campos
    public function attributes(): array
    {
        return [
            'search' => 'busqueda',
            'area_id' => 'area',
            'position_id' => 'cargo',
            'location_id' => 'local',
            'start_date_from' => 'fecha inicial',
            'start_date_to' => 'fecha final',
            'sort_by' => 'campo de ordenamiento',
            'sort_direction' => 'direccion de ordenamiento',
        ];
    }
}
