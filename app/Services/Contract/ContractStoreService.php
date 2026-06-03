<?php

namespace App\Services\Contract;

use App\Models\Contract;

class ContractStoreService
{
    //! Registrar contrato
    public function handle(array $data): Contract
    {
        // Preservar historial de contratos
        return Contract::create([
            'employee_id' => $data['employee_id'],
            'contract_type' => $data['contract_type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
        ]);
    }
}
