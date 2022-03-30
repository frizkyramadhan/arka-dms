<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::create([
            'dept_name' => 'Accounting',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Corporate Secretary',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Design & Construction',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Engineering',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Finance',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Human Capital & Support',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Internal Audit & System',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Information Technology',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Logistic',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Plant',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Procurement',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Relation & Coordination',
            'dept_status' => 'active'
        ]);
        Department::create([
            'dept_name' => 'Safety, Health & Environment',
            'dept_status' => 'active'
        ]);
    }
}
