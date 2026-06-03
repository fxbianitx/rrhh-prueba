<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Area;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Position;
use App\Services\Employee\EmployeeDeactivateService;
use App\Services\Employee\EmployeeStoreService;
use App\Services\Employee\EmployeeUpdateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    //! Mostrar listado de empleados
    public function index(): View
    {
        $employees = Employee::query()
            ->with(['area', 'position', 'location', 'contracts'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(16);

        $areas = Area::query()->orderBy('name')->get();
        $positions = Position::query()->orderBy('name')->get();
        $locations = Location::query()->orderBy('name')->get();
        $currentSortBy = 'full_name';
        $currentSortDirection = 'asc';

        return view(
            'employees.index',
            compact('employees', 'areas', 'positions', 'locations', 'currentSortBy', 'currentSortDirection')
        );
    }

    //! Mostrar formulario de empleado
    public function create(): View
    {
        $areas = Area::query()->orderBy('name')->get();
        $positions = Position::query()->orderBy('name')->get();
        $locations = Location::query()->orderBy('name')->get();

        return view('employees.create', compact('areas', 'positions', 'locations'));
    }

    //! Registrar empleado
    public function store(EmployeeStoreRequest $request, EmployeeStoreService $service): RedirectResponse
    {
        $employee = $service->handle($request->validated());

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Empleado registrado correctamente.');
    }

    //! Mostrar detalle del empleado
    public function show(Employee $employee): View
    {
        // Cargar empleado con su area, puesto y contratos
        $employee->load([
            'area',
            'position',
            'location',
            'contracts' => function ($query) {
                $query->orderByDesc('start_date');
            },
        ]);

        $contracts = $employee->contracts->map(function ($contract) {
            $contract->contract_status = is_null($contract->end_date) || $contract->end_date >= now()->toDateString()
                ? 'Vigente'
                : 'Finalizado';

            return $contract;
        });

        return view('employees.show', compact('employee', 'contracts'));
    }

    //! Mostrar formulario de edicion
    public function edit(Employee $employee): View
    {
        $areas = Area::query()->orderBy('name')->get();
        $positions = Position::query()->orderBy('name')->get();
        $locations = Location::query()->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'areas', 'positions', 'locations'));
    }

    //! Actualizar empleado
    public function update(EmployeeUpdateRequest $request, Employee $employee, EmployeeUpdateService $updateService): RedirectResponse 
    {
        $updateService->handle($employee, $request->validated());

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    //! Dar de baja empleado
    public function destroy(Employee $employee, EmployeeDeactivateService $service): RedirectResponse
    {
        $service->handle($employee);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado dado de baja correctamente.');
    }
}
