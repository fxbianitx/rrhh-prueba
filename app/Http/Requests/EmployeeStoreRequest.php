<?php

namespace App\Http\Requests;

use App\Enums\ContractType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EmployeeStoreRequest extends FormRequest
{
    //! Autorizar registro de empleado
    public function authorize(): bool
    {
        return true;
    }

    //! Validar datos del empleado
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'digits:8', 'unique:employees,dni'],
            'email' => ['required', 'email'],
            'birth_date' => ['required', 'date'],
            'area_id' => ['required', 'exists:areas,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'location_id' => ['required', 'exists:locations,id'],
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
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.string' => 'El nombre debe ser un texto valido.',
            'first_name.max' => 'El nombre no puede superar los 100 caracteres.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.string' => 'El apellido debe ser un texto valido.',
            'last_name.max' => 'El apellido no puede superar los 100 caracteres.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.digits' => 'El DNI debe tener exactamente 8 digitos.',
            'dni.unique' => 'El DNI ya se encuentra registrado.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo debe tener un formato valido.',
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'birth_date.date' => 'La fecha de nacimiento debe ser valida.',
            'area_id.required' => 'El area es obligatoria.',
            'area_id.exists' => 'El area seleccionada no es valida.',
            'position_id.required' => 'El cargo es obligatorio.',
            'position_id.exists' => 'El cargo seleccionado no es valido.',
            'location_id.required' => 'El local es obligatorio.',
            'location_id.exists' => 'El local seleccionado no es valido.',
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
            'first_name' => 'nombre',
            'last_name' => 'apellido',
            'dni' => 'DNI',
            'email' => 'correo',
            'birth_date' => 'fecha de nacimiento',
            'area_id' => 'area',
            'position_id' => 'cargo',
            'location_id' => 'local',
            'contract_type' => 'tipo de contrato',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de fin',
        ];
    }
}
