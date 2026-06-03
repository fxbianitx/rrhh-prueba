<?php

namespace App\Services\Employee;

use App\Models\Employee;

class EmployeeUpdateService
{
    //! Actualizar informacion del empleado
    public function handle(Employee $employee, array $data): Employee
    {
        $employee->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'dni' => $data['dni'],
            'email' => $data['email'],
            'birth_date' => $data['birth_date'],
            'area_id' => $data['area_id'],
            'position_id' => $data['position_id'],
            'location_id' => $data['location_id'],
        ]);

        return $employee->load(['area', 'position', 'location']);
    }
}
