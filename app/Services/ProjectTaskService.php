<?php

namespace App\Services;

use App\Models\ProjectTask;
use App\Repositories\ProjectTaskRepository;
use Illuminate\Database\Eloquent\Collection;

class ProjectTaskService
{
    private $repo;

    public function __construct(ProjectTaskRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * get project's task.
     *
     * @param int $id
     * @return array
     */
    public function getProjectTasks(int $id)
    {
        $tasks = $this->repo->getProjectTasks($id)
                            ->with('story')
                            ->get()
                            ->sortBy('absolute_day');

        return  $this->formatTasks($tasks);
    }

    /**
     * format tasks collection fo array task.
     *
     * @param Collection $tasks
     * @return array
     */
    protected function formatTasks(Collection $tasks)
    {
        $return = [];
        $remainDays = 0;
        foreach ($tasks as $task) { 
            // if remainDays > 0, move to next index, and minus remainDays
            if ($remainDays) {
                $remainDays--;
                continue;
            }
            if ($task->story_id === null) {
                $return[] = [
                    'id' => $task->id,
                    'absolute_day' => $task->absolute_day,
                    'day' => $task->absolute_day,
                    'type' => ProjectTask::TASK_TYPE,
                    'name' => $task->name,
                    'story' => ''
                ];
            } else {
                // if $task->story !== null, group to days adn set remainDays
                $remainDays = $task->story->take_days - 1;
                $endDay = $task->absolute_day + $task->story->take_days - 1;
                $return[] = [
                    'id' => $task->id,
                    'absolute_day' => $task->absolute_day,
                    'day' => "$task->absolute_day - $endDay",
                    'type' => ProjectTask::STORY_TYPE,
                    'name' => $task->story->name,
                    'story' => $task->story
                ];
            }
        }

        return $return;
    }

    /**
     * update Tasks.
     *
     * @param array $data
     * @return void
     */
    public function updateTasks(array $data)
    {
        foreach ($data as $task) {
            $originalTask = $this->repo->find($task['id']);
            $this->repo->save($originalTask, ['absolute_day' => $task['absolute_day'], 'name' => $task['name']]);
        }
    }
}
