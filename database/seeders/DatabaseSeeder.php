<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transmittal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProjectSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(UnitSeeder::class);

        User::factory(50)->create();
        // Transmittal::factory(500)->create();
    }
}
