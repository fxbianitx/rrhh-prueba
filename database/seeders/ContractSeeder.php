<?php

namespace Database\Seeders;

use App\Enums\ContractType;
use App\Models\Contract;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    //! Registrar historial de contratos
    public function run(): void
    {
        $employees = Employee::query()->orderBy('id')->get();

        foreach ($employees as $index => $employee) {
            $contracts = $this->contractTimeline($index);

            foreach ($contracts as $contractData) {
                $attributes = Contract::factory()->make([
                    'employee_id' => $employee->id,
                    'contract_type' => $contractData['contract_type'],
                    'start_date' => $contractData['start_date'],
                    'end_date' => $contractData['end_date'],
                ])->toArray();

                Contract::query()->firstOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'contract_type' => $contractData['contract_type'],
                        'start_date' => $contractData['start_date'],
                    ],
                    $attributes
                );
            }
        }
    }

    //! Preparar historial de contratos
    private function contractTimeline(int $index): array
    {
        $baseStartDate = Carbon::create(2019, 1, 1)->addMonths($index);
        $contractTypes = ContractType::values();
        $contractCount = ($index % 5) + 1;
        $hasActiveContract = fake()->boolean(80);
        $contracts = [];
        $currentStartDate = $baseStartDate->copy();

        foreach (range(1, $contractCount) as $position) {
            $isLastContract = $position === $contractCount;
            $contractType = $contractTypes[($index + $position - 1) % count($contractTypes)];

            if ($hasActiveContract && $isLastContract) {
                $contracts[] = [
                    'contract_type' => ContractType::INDEFINIDO->value,
                    'start_date' => $currentStartDate->toDateString(),
                    'end_date' => null,
                ];

                continue;
            }

            $endDate = $currentStartDate->copy()->addMonths(11);

            $contracts[] = [
                'contract_type' => $contractType,
                'start_date' => $currentStartDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ];

            $currentStartDate = $endDate->copy()->addDay();
        }

        return $contracts;
    }
}
