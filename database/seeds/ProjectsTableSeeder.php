<?php

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/data/projects.json');
        $data = json_decode($json);
        foreach ($data as $project) {
            $projects[] = ['id' => $project->id, 'name' => $project->name];
        }
        Project::insert($projects);
    }
}
