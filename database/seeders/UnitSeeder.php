<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'unit_name' => 'Hilux',
            'unit_status' => 1
        ]);
        Unit::create([
            'unit_name' => 'Loboy',
            'unit_status' => 1
        ]);
        Unit::create([
            'unit_name' => 'Granmax',
            'unit_status' => 1
        ]);
        Unit::create([
            'unit_name' => 'Truck PS',
            'unit_status' => 1
        ]);
        Unit::create([
            'unit_name' => 'Truck Fuso',
            'unit_status' => 1
        ]);
        Unit::create([
            'unit_name' => 'Truck Tronton',
            'unit_status' => 1
        ]);
        Unit::create([
            'unit_name' => 'Shaft Loader',
            'unit_status' => 1
        ]);
    }
}
