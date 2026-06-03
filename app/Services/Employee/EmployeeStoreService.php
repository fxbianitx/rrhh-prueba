<?php

namespace App\Services\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeStoreService
{
    //! Crear empleado
    public function handle(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $employeeData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'dni' => $data['dni'],
                'email' => $data['email'],
                'birth_date' => $data['birth_date'],
                'area_id' => $data['area_id'],
                'position_id' => $data['position_id'],
                'location_id' => $data['location_id'],
            ];

            $employee = Employee::create($employeeData);

            // Registrar contrato inicial
            $employee->contracts()->create([
                'contract_type' => $data['contract_type'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
            ]);

            return $employee->load(['area', 'position', 'location', 'contracts']);
        });
    }
}
