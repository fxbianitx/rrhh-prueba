<?php

namespace App\Services\Contract;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContractRenewService
{
    public function __construct(
        private ContractStoreService $contractStoreService
    ) { }

    //! Registrar renovacion de contrato
    public function handle(array $data): Contract
    {
        return DB::transaction(function () use ($data) {
            // Obtener fecha
            $newStartDate = Carbon::parse($data['start_date']);
            
            // Buscar un contrato activo
            $activeContract = $this->findActiveContract($data['employee_id'], $newStartDate);

            // Cerrar contrato vigente
            if ($activeContract && Carbon::parse($activeContract->start_date)->lt($newStartDate)) {
                $activeContract->update([
                    'end_date' => $newStartDate->copy()->subDay()->toDateString(),
                ]);
            }

            // Registrar nuevo contrato
            return $this->contractStoreService->handle($data);
        });
    }

    //! Buscar si el empleado tiene un contrato activo
    private function findActiveContract(int $employeeId, Carbon $newStartDate): ?Contract
    {
        return Contract::query()
            ->where('employee_id', $employeeId)
            ->whereDate('start_date', '<=', $newStartDate->toDateString())
            ->where(function ($query) use ($newStartDate) {
                $query
                ->whereNull('end_date')
                ->orWhereDate('end_date', '>=', $newStartDate->toDateString());
            })
            ->latest('start_date')
            ->lockForUpdate()
            ->first();
    }
}
