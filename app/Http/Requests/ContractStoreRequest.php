<?php

namespace App\Http\Requests;

use App\Enums\ContractType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ContractStoreRequest extends FormRequest
{
    //! Autorizar registro de contrato
    public function authorize(): bool
    {
        return true;
    }

    //! Validar datos del contrato
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'contract_type' => ['required', new Enum(ContractType::class)],
            'start_date' => ['required', 'date'],
            'end_date' => [
                Rule::requiredIf($this->input('contract_type') !== ContractType::INDEFINIDO->value),
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ];
    }

    //! Mostrar mensajes de validacion
    public function messages(): array
    {
        return [
            'employee_id.required' => 'El empleado es obligatorio.',
            'employee_id.exists' => 'El empleado seleccionado no es valido.',
            'contract_type.required' => 'El tipo de contrato es obligatorio.',
            'contract_type.enum' => 'El tipo de contrato seleccionado no es valido.',
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio debe ser valida.',
            'end_date.required' => 'La fecha de fin es obligatoria para este tipo de contrato.',
            'end_date.date' => 'La fecha de fin debe ser valida.',
            'end_date.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
        ];
    }

    //! Mostrar nombres de campos
    public function attributes(): array
    {
        return [
            'employee_id' => 'empleado',
            'contract_type' => 'tipo de contrato',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de fin',
        ];
    }
}
