<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        //! Registrar areas base
        $areas = [
            ['name' => 'Recursos Humanos'],
            ['name' => 'Tecnologia'],
            ['name' => 'Finanzas'],
            ['name' => 'Operaciones'],
        ];

        foreach ($areas as $area) {
            Area::firstOrCreate($area);
        }
    }
}
