<?php

namespace Database\Factories;

use App\Enums\ContractType;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;


class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-4 years', '-6 months');
        $isActive = fake()->boolean(35);
        $endDate = $isActive
            ? null
            : fake()->dateTimeBetween($startDate, 'now')->format('Y-m-d');

        return [
            'contract_type' => fake()->randomElement(ContractType::values()),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate,
        ];
    }
}
