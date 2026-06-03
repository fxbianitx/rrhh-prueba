<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractStoreRequest;
use App\Models\Employee;
use App\Services\Contract\ContractRenewService;
use App\Services\Contract\ContractStoreService;
use Illuminate\Http\RedirectResponse;

class ContractController extends Controller
{
    //! Registrar contrato
    public function store(ContractStoreRequest $request, ContractStoreService $storeService): RedirectResponse
    {
        $contract = $storeService->handle($request->validated());

        return redirect()
            ->route('employees.show', $contract->employee_id)
            ->with('success', 'Contrato registrado correctamente.');
    }

    //! Registrar renovacion de contrato
    public function renew(ContractStoreRequest $request, Employee $employee, ContractRenewService $renewService): RedirectResponse
    {
        $data = $request->validated();
        $data['employee_id'] = $employee->id;

        $contract = $renewService->handle($data);

        return redirect()
            ->route('employees.show', $contract->employee_id)
            ->with('success', 'Renovacion registrada correctamente.');
    }
}
