<?php

namespace Database\Seeders;

use App\Models\Series;
use Illuminate\Database\Seeder;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Series::create([
            'prefix' => 'TF',
            'name' => 'Transmittal Form',
            'status' => 'active'
        ]);
    }
}
