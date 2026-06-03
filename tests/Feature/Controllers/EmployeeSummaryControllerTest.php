<?php

namespace Tests\Feature\Controllers;

use App\Enums\ContractType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithEmployeeData;
use Tests\TestCase;

class EmployeeSummaryControllerTest extends TestCase
{
    use InteractsWithEmployeeData;
    use RefreshDatabase;

    public function test_muestra_vista_de_resumen_sin_fecha_de_corte(): void
    {
        $response = $this->get(route('employees.summary'));

        $response->assertOk();
        $response->assertViewIs('summary.index');
    }

    public function test_retorna_empleados_activos_agrupados_por_area(): void
    {
        $catalogos = $this->crearCatalogos();

        $employeeActiveAreaOne = $this->crearEmpleado($catalogos, [
            'dni' => '71000001',
            'email' => 'uno@rhnubego.com',
            'area_id' => $catalogos['area']->id,
        ]);
        $employeeActiveAreaTwo = $this->crearEmpleado($catalogos, [
            'first_name' => 'Mario',
            'last_name' => 'Quispe Leon',
            'dni' => '71000002',
            'email' => 'dos@rhnubego.com',
            'area_id' => $catalogos['second_area']->id,
        ]);
        $employeeInactive = $this->crearEmpleado($catalogos, [
            'first_name' => 'Nora',
            'last_name' => 'Paz Rojas',
            'dni' => '71000003',
            'email' => 'tres@rhnubego.com',
            'area_id' => $catalogos['area']->id,
        ]);

        $this->crearContrato($employeeActiveAreaOne, [
            'contract_type' => ContractType::INDEFINIDO->value,
            'start_date' => '2024-01-01',
            'end_date' => null,
        ]);
        $this->crearContrato($employeeActiveAreaTwo, [
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2024-01-01',
            'end_date' => '2024-12-31',
        ]);
        $this->crearContrato($employeeInactive, [
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2023-01-01',
            'end_date' => '2023-12-31',
        ]);

        $response = $this->getJson(route('employees.summary', [
            'cutoff_date' => '2024-06-30',
        ]));

        $response->assertOk();
        $response->assertJson([
            'cutoff_date' => '2024-06-30',
            'total_employees' => 2,
        ]);
        $response->assertJsonFragment([
            'area_name' => $catalogos['area']->name,
            'total' => 1,
        ]);
        $response->assertJsonFragment([
            'area_name' => $catalogos['second_area']->name,
            'total' => 1,
        ]);
    }

    public function test_considera_contratos_sin_fecha_fin_como_activos(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos, [
            'dni' => '71001001',
            'email' => 'vigente@rhnubego.com',
        ]);

        $this->crearContrato($employee, [
            'contract_type' => ContractType::INDEFINIDO->value,
            'start_date' => '2022-01-01',
            'end_date' => null,
        ]);

        $response = $this->getJson(route('employees.summary', [
            'cutoff_date' => '2024-10-10',
        ]));

        $response->assertOk();
        $response->assertJson([
            'total_employees' => 1,
        ]);
    }

    public function test_valida_fecha_de_corte(): void
    {
        $response = $this->getJson(route('employees.summary', [
            'cutoff_date' => 'fecha-invalida',
        ]));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('cutoff_date');
    }
}
