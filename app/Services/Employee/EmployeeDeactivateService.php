<?php

namespace App\Services\Employee;

use App\Models\Employee;

class EmployeeDeactivateService
{
    //! Dar de baja empleado
    public function handle(Employee $employee): void
    {
        // Preservar historial del empleado
        $employee->delete();
    }
}
