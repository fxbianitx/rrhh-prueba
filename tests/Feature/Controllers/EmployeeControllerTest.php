<?php

namespace Tests\Feature\Controllers;

use App\Enums\ContractType;
use App\Models\Contract;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithEmployeeData;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use InteractsWithEmployeeData;
    use RefreshDatabase;

    public function test_muestra_el_listado_de_empleados(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);

        $response = $this->get(route('employees.index'));

        $response->assertOk();
        $response->assertViewIs('employees.index');
        $response->assertSeeText($employee->last_name);
    }

    public function test_muestra_el_formulario_de_registro(): void
    {
        $this->crearCatalogos();

        $response = $this->get(route('employees.create'));

        $response->assertOk();
        $response->assertViewIs('employees.create');
        $response->assertSeeText('Contrato inicial');
    }

    public function test_registra_empleado_con_contrato_inicial(): void
    {
        $catalogos = $this->crearCatalogos();

        $response = $this->post(route('employees.store'), [
            'first_name' => 'Lucia',
            'last_name' => 'Ramos Diaz',
            'dni' => '76543210',
            'email' => 'lucia.ramos@rhnubego.com',
            'birth_date' => '1995-03-10',
            'area_id' => $catalogos['area']->id,
            'position_id' => $catalogos['position']->id,
            'location_id' => $catalogos['location']->id,
            'contract_type' => ContractType::PLAZO_FIJO->value,
            'start_date' => '2024-02-01',
            'end_date' => '2024-12-31',
        ]);

        $employee = Employee::where('dni', '76543210')->firstOrFail();

        $response->assertRedirect(route('employees.show', $employee));
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'email' => 'lucia.ramos@rhnubego.com',
        ]);
        $this->assertDatabaseHas('contracts', [
            'employee_id' => $employee->id,
            'contract_type' => ContractType::PLAZO_FIJO->value,
            'start_date' => '2024-02-01',
        ]);
    }

    public function test_valida_fecha_fin_para_contrato_no_indefinido(): void
    {
        $catalogos = $this->crearCatalogos();

        $response = $this->from(route('employees.create'))->post(route('employees.store'), [
            'first_name' => 'Luis',
            'last_name' => 'Mora Salas',
            'dni' => '75432109',
            'email' => 'luis.mora@rhnubego.com',
            'birth_date' => '1991-08-20',
            'area_id' => $catalogos['area']->id,
            'position_id' => $catalogos['position']->id,
            'location_id' => $catalogos['location']->id,
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2024-04-01',
            'end_date' => null,
        ]);

        $response->assertRedirect(route('employees.create'));
        $response->assertSessionHasErrors('end_date');
        $this->assertDatabaseCount('employees', 0);
    }

    public function test_muestra_empleado_e_historial_de_contratos(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);
        $this->crearContrato($employee, [
            'contract_type' => ContractType::TEMPORAL->value,
            'start_date' => '2023-01-01',
            'end_date' => '2023-12-31',
        ]);
        $this->crearContrato($employee, [
            'contract_type' => ContractType::INDEFINIDO->value,
            'start_date' => '2024-01-01',
            'end_date' => null,
        ]);

        $response = $this->get(route('employees.show', $employee));

        $response->assertOk();
        $response->assertViewIs('employees.show');
        $response->assertSeeText('Historial de contratos');
        $response->assertSeeText(ContractType::INDEFINIDO->value);
        $response->assertSeeText('Vigente');
    }

    public function test_muestra_el_formulario_de_edicion(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);

        $response = $this->get(route('employees.edit', $employee));

        $response->assertOk();
        $response->assertViewIs('employees.edit');
        $response->assertSeeText('Editar empleado');
    }

    public function test_actualiza_empleado_sin_modificar_historial_de_contratos(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);
        $this->crearContrato($employee);

        $response = $this->put(route('employees.update', $employee), [
            'first_name' => 'Ana Maria',
            'last_name' => 'Perez Torres',
            'dni' => $employee->dni,
            'email' => 'ana.maria@rhnubego.com',
            'birth_date' => '1994-05-12',
            'area_id' => $catalogos['second_area']->id,
            'position_id' => $catalogos['second_position']->id,
            'location_id' => $catalogos['second_location']->id,
        ]);

        $response->assertRedirect(route('employees.index'));
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'first_name' => 'Ana Maria',
            'last_name' => 'Perez Torres',
            'email' => 'ana.maria@rhnubego.com',
            'area_id' => $catalogos['second_area']->id,
        ]);
        $this->assertEquals(1, Contract::where('employee_id', $employee->id)->count());
    }

    public function test_da_de_baja_empleado_y_preserva_contratos(): void
    {
        $catalogos = $this->crearCatalogos();
        $employee = $this->crearEmpleado($catalogos);
        $contract = $this->crearContrato($employee);

        $response = $this->delete(route('employees.destroy', $employee));

        $response->assertRedirect(route('employees.index'));
        $response->assertSessionHas('success', 'Empleado dado de baja correctamente.');
        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
        $this->assertDatabaseHas('contracts', ['id' => $contract->id]);
    }
}
