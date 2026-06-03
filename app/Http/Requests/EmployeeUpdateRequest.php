<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    //! Autorizar actualizacion de empleado
    public function authorize(): bool
    {
        return true;
    }

    //! Validar datos del empleado
    public function rules(): array
    {
        $employee = $this->route('employee');
        $employeeId = $employee instanceof Employee ? $employee->id : $employee;

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'digits:8', Rule::unique('employees', 'dni')->ignore($employeeId)],
            'email' => ['required', 'email'],
            'birth_date' => ['required', 'date'],
            'area_id' => ['required', 'exists:areas,id'],
            'position_id' => ['required', 'exists:positions,id'],
            'location_id' => ['required', 'exists:locations,id'],
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
            'dni.unique' => 'El DNI ya se encuentra registrado por otro empleado.',
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
        ];
    }
}
