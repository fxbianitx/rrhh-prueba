<?php

namespace Tests\Feature\Controllers;

use App\Enums\ContractType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithEmployeeData;
use Tests\TestCase;

class EmployeeSearchControllerTest extends TestCase
{
    use InteractsWithEmployeeData;
    use RefreshDatabase;

    public function test_filtra_empleados_por_area(): void
    {
        $catalogos = $this->crearCatalogos();
        $employeeInArea = $this->crearEmpleado($catalogos, [
            'dni' => '70000001',
            'email' => 'ana.area@rhnubego.com',
        ]);
        $employeeOutArea = $this->crearEmpleado($catalogos, [
            'first_name' => 'Carlos',
            'last_name' => 'Mendez Ruiz',
            'dni' => '70000002',
            'email' => 'carlos.area@rhnubego.com',
            'area_id' => $catalogos['second_area']->id,
        ]);
        $this->crearContrato($employeeInArea);
        $this->crearContrato($employeeOutArea);

        $response = $this->getJson(route('employees.search', [
            'area_id' => $catalogos['area']->id,
        ]));

        $response->assertOk();
        $response->assertJsonStructure(['html']);
        $response->assertSeeText($employeeInArea->last_name);
        $response->assertDontSeeText($employeeOutArea->last_name);
    }

    public function test_filtra_empleados_por_nombre_o_dni(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos, [
            'first_name' => 'Martha',
            'last_name' => 'Lopez Diaz',
            'dni' => '70123456',
            'email' => 'martha.lopez@rhnubego.com',
        ]);
        $otherEmployee = $this->crearEmpleado($catalogos, [
            'first_name' => 'Pedro',
            'last_name' => 'Santos',
            'dni' => '70987654',
            'email' => 'pedro.santos@rhnubego.com',
        ]);
        $this->crearContrato($employee);
        $this->crearContrato($otherEmployee);

        $response = $this->getJson(route('employees.search', [
            'search' => '70123456',
        ]));

        $response->assertOk();
        $response->assertSeeText($employee->last_name);
        $response->assertDontSeeText($otherEmployee->last_name);
    }

    public function test_filtra_empleados_por_rango_de_fechas_de_contrato(): void
    {
        $catalogos = $this->crearCatalogos();
        $employeeInRange = $this->crearEmpleado($catalogos, [
            'dni' => '70001001',
            'email' => 'en.rango@rhnubego.com',
        ]);
        $employeeOutRange = $this->crearEmpleado($catalogos, [
            'first_name' => 'Diana',
            'last_name' => 'Rios Flores',
            'dni' => '70001002',
            'email' => 'fuera.rango@rhnubego.com',
        ]);
        $this->crearContrato($employeeInRange, [
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2024-03-15',
            'end_date' => '2024-12-31',
        ]);
        $this->crearContrato($employeeOutRange, [
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2023-01-15',
            'end_date' => '2023-12-31',
        ]);

        $response = $this->getJson(route('employees.search', [
            'start_date_from' => '2024-01-01',
            'start_date_to' => '2024-06-30',
        ]));

        $response->assertOk();
        $response->assertSeeText($employeeInRange->last_name);
        $response->assertDontSeeText($employeeOutRange->last_name);
    }

    public function test_ordena_empleados_por_dni_descendente(): void
    {
        $catalogos = $this->crearCatalogos();
        $employeeA = $this->crearEmpleado($catalogos, [
            'first_name' => 'Alicia',
            'last_name' => 'Bravo',
            'dni' => '70002001',
            'email' => 'alicia@rhnubego.com',
        ]);
        $employeeB = $this->crearEmpleado($catalogos, [
            'first_name' => 'Bruno',
            'last_name' => 'Campos',
            'dni' => '70002099',
            'email' => 'bruno@rhnubego.com',
        ]);
        $this->crearContrato($employeeA);
        $this->crearContrato($employeeB);

        $response = $this->getJson(route('employees.search', [
            'sort_by' => 'dni',
            'sort_direction' => 'desc',
        ]));

        $response->assertOk();
        $html = $response->json('html');
        $this->assertNotFalse(strpos($html, '70002099'));
        $this->assertNotFalse(strpos($html, '70002001'));
        $this->assertTrue(strpos($html, '70002099') < strpos($html, '70002001'));
    }
}
