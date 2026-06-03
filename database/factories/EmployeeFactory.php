<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'dni' => fake()->unique()->numerify('########'),
            'email' => fake()->unique()->safeEmail(),
            'birth_date' => fake()->dateTimeBetween('-55 years', '-22 years')->format('Y-m-d'),
            'area_id' => Area::query()->inRandomOrder()->value('id'),
            'position_id' => Position::query()->inRandomOrder()->value('id'),
            'location_id' => Location::query()->inRandomOrder()->value('id'),
        ];
    }
}
