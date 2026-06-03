<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        //! Registrar locales base
        $locations = [
            ['name' => 'Lima'],
            ['name' => 'Arequipa'],
            ['name' => 'Trujillo'],
            ['name' => 'Cusco'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate($location);
        }
    }
}
