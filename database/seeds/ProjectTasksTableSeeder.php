<?php

use Illuminate\Database\Seeder;
use App\Models\ProjectTask;

class ProjectTasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/project_tasks.json");
        $data = json_decode($json);
        foreach($data as $task){
            $projectTasks[] = ['id' => $task->id, 'project_id' => $task->project_id, 'absolute_day' => $task->absolute_day, 'name' => $task->name, 'story_id' => $task->story_id];
        }

        ProjectTask::insert($projectTasks);
    }
}
