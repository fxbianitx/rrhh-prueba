<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        //! Registrar cargos base
        $positions = [
            ['name' => 'Analista'],
            ['name' => 'Coordinador'],
            ['name' => 'Asistente'],
            ['name' => 'Jefe'],
        ];

        foreach ($positions as $position) {
            Position::firstOrCreate($position);
        }
    }
}
