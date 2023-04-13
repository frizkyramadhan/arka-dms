<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'full_name' => 'Administrator',
            'email' => 'administrator@arka-dms.dev',
            'password' => bcrypt('admin'),
            'level' => 'administrator',
            'project_id' => 1,
            'department_id' => 7
        ]);
        User::create([
            'full_name' => 'Frizky Ramadhan',
            'email' => 'frizky.ramadhan@arka.co.id',
            'password' => bcrypt('admin'),
            'level' => 'administrator',
            'project_id' => 1,
            'department_id' => 7
        ]);
        User::create([
            'full_name' => 'Suyanto',
            'email' => 'suyanto@arka.co.id',
            'password' => bcrypt('admin'),
            'level' => 'administrator',
            'project_id' => 1,
            'department_id' => 7
        ]);
    }
}
