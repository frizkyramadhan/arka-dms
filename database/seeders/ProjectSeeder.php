<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::create([
            'project_code' => '000H',
            'project_name' => 'HO - Balikpapan'
        ]);
        Project::create([
            'project_code' => '001H',
            'project_name' => 'BO - Jakarta'
        ]);
        Project::create([
            'project_code' => '017C',
            'project_name' => 'KPUC - Malinau'
        ]);
        Project::create([
            'project_code' => '021C',
            'project_name' => 'SBI - Bogor'
        ]);
        Project::create([
            'project_code' => '022C',
            'project_name' => 'GPK - Melak'
        ]);
        Project::create([
            'project_code' => '023C',
            'project_name' => 'BEK - Muara Lawa'
        ]);
        Project::create([
            'project_code' => 'APS',
            'project_name' => 'APS - Kariangau'
        ]);
    }
}
