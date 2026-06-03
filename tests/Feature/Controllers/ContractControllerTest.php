<?php

namespace Tests\Feature\Controllers;

use App\Enums\ContractType;
use App\Models\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithEmployeeData;
use Tests\TestCase;

class ContractControllerTest extends TestCase
{
    use InteractsWithEmployeeData;
    use RefreshDatabase;

    public function test_registra_nuevo_contrato_para_empleado_existente(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);

        $response = $this->post(route('contracts.store'), [
            'employee_id' => $employee->id,
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2024-06-01',
            'end_date' => '2024-12-31',
        ]);

        $response->assertRedirect(route('employees.show', $employee));
        $this->assertDatabaseHas('contracts', [
            'employee_id' => $employee->id,
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2024-06-01',
            'end_date' => '2024-12-31',
        ]);
    }

    public function test_renueva_contrato_y_preserva_historial(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);
        $activeContract = $this->crearContrato($employee, [
            'contract_type' => ContractType::PLAZO_FIJO->value,
            'start_date' => '2024-01-01',
            'end_date' => null,
        ]);

        $response = $this->post(route('contracts.renew', $employee), [
            'employee_id' => $employee->id,
            'contract_type' => ContractType::INDEFINIDO->value,
            'start_date' => '2024-07-01',
            'end_date' => null,
        ]);

        $response->assertRedirect(route('employees.show', $employee));
        $this->assertDatabaseHas('contracts', [
            'id' => $activeContract->id,
            'end_date' => '2024-06-30',
        ]);
        $this->assertDatabaseHas('contracts', [
            'employee_id' => $employee->id,
            'contract_type' => ContractType::INDEFINIDO->value,
            'start_date' => '2024-07-01',
            'end_date' => null,
        ]);
        $this->assertEquals(2, Contract::where('employee_id', $employee->id)->count());
    }

    public function test_renueva_contrato_indefinido_sin_fecha_fin(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);
        $this->crearContrato($employee, [
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2024-01-01',
            'end_date' => '2024-06-30',
        ]);

        $response = $this->post(route('contracts.renew', $employee), [
            'employee_id' => $employee->id,
            'contract_type' => ContractType::INDEFINIDO->value,
            'start_date' => '2024-07-01',
            'end_date' => null,
        ]);

        $response->assertRedirect(route('employees.show', $employee));
        $this->assertDatabaseHas('contracts', [
            'employee_id' => $employee->id,
            'contract_type' => ContractType::INDEFINIDO->value,
            'end_date' => null,
        ]);
    }
}
