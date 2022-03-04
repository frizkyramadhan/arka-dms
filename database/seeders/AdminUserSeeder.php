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
            'full_name' => 'Super Admin',
            'email' => 'superadmin@arka-doc.dev',
            'password' => bcrypt('superadmin'),
            'project_id' => 1
        ]);
    }
}
