<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        //! Registrar empleados de prueba
        $areas = Area::query()->orderBy('id')->get();
        $positions = Position::query()->orderBy('id')->get();
        $locations = Location::query()->orderBy('id')->get();

        $employees = $this->employeeData();

        foreach ($employees as $employeeData) {
            $area = $areas->random();
            $position = $positions->random();
            $location = $locations->random();

            $attributes = Employee::factory()->make([
                'first_name' => $employeeData['first_name'],
                'last_name' => $employeeData['last_name'],
                'dni' => $employeeData['dni'],
                'email' => $employeeData['email'],
                'birth_date' => $employeeData['birth_date'],
                'area_id' => $area->id,
                'position_id' => $position->id,
                'location_id' => $location->id,
            ])->toArray();

            Employee::query()->updateOrCreate(
                ['dni' => $employeeData['dni']],
                $attributes
            );
        }
    }

    //! Preparar datos de empleados
    private function employeeData(): Collection
    {
        $faker = fake('es_PE');

        return collect(range(1, 50))->map(function () use ($faker) {
            $firstName = $faker->firstName();
            $lastName1 = $faker->lastName();
            $lastName2 = $faker->lastName();

            $emailPrefix = Str::of($firstName . '.' . $lastName1)
                ->lower()
                ->ascii()
                ->replace(' ', '')
                ->replace("'", '')
                ->value();

            return [
                'first_name' => $firstName,
                'last_name' => $lastName1 .' '. $lastName2,
                'dni' => $faker->unique()->numerify('7#######'),
                'email' => $emailPrefix . '@rhnubego.com',
                'birth_date' => $faker->dateTimeBetween('-55 years', '-22 years')->format('Y-m-d'),
            ];
        });
    }
}
