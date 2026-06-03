<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeSummaryRequest extends FormRequest
{
    //! Autorizar consulta de resumen
    public function authorize(): bool
    {
        return true;
    }

    //! Validar fecha de corte
    public function rules(): array
    {
        return [
            'cutoff_date' => ['required', 'date'],
        ];
    }

    //! Mostrar mensajes de validacion
    public function messages(): array
    {
        return [
            'cutoff_date.required' => 'La fecha de corte es obligatoria.',
            'cutoff_date.date' => 'La fecha de corte debe ser valida.',
        ];
    }

    //! Mostrar nombres de campos
    public function attributes(): array
    {
        return [
            'cutoff_date' => 'fecha de corte',
        ];
    }
}
