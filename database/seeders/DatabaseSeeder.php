<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //! Registrar catalogos base
        $this->call([
            AreaSeeder::class,
            PositionSeeder::class,
            LocationSeeder::class,
            EmployeeSeeder::class,
            ContractSeeder::class,
        ]);
    }
}
