<?php

namespace Tests\Concerns;

use App\Enums\ContractType;
use App\Models\Area;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Position;

trait InteractsWithEmployeeData
{
    protected function crearCatalogos(): array
    {
        return [
            'area' => Area::create(['name' => 'Tecnologia']),
            'second_area' => Area::create(['name' => 'Finanzas']),
            'position' => Position::create(['name' => 'Analista']),
            'second_position' => Position::create(['name' => 'Coordinador']),
            'location' => Location::create(['name' => 'Lima']),
            'second_location' => Location::create(['name' => 'Arequipa']),
        ];
    }

    protected function crearEmpleado(array $catalogos, array $attributes = []): Employee
    {
        return Employee::factory()->create(array_merge([
            'first_name' => 'Ana',
            'last_name' => 'Perez Soto',
            'dni' => '71234567',
            'email' => 'ana.perez@rhnubego.com',
            'birth_date' => '1994-05-12',
            'area_id' => $catalogos['area']->id,
            'position_id' => $catalogos['position']->id,
            'location_id' => $catalogos['location']->id,
        ], $attributes));
    }

    protected function crearContrato(Employee $employee, array $attributes = []): Contract
    {
        return Contract::factory()->create(array_merge([
            'employee_id' => $employee->id,
            'contract_type' => ContractType::PLAZO_FIJO->value,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ], $attributes));
    }
}
