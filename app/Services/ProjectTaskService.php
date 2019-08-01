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
            if ($remainDays) {
                $remainDays--;
                continue;
            }
            if ($task->story_id === null) {
                $return[] = [
                    'id' => $task->id,
                    'day' => $task->absolute_day,
                    'type' => ProjectTask::TASK_TYPE,
                    'name' => $task->name,
                ];
            } else {
                $remainDays = $task->story->take_days - 1;
                $endDay = $task->absolute_day + $task->story->take_days - 1;
                $return[] = [
                    'id' => $task->id,
                    'day' => "$task->absolute_day - $endDay",
                    'type' => ProjectTask::STORY_TYPE,
                    'name' => $task->story->name,
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
        $absolute_day = 0;
        foreach ($data as $index => $task) {
            if (! $absolute_day) {
                $absolute_day = $index + 1;
            }
            if ($task['type'] == ProjectTask::TASK_TYPE) {
                $originalTask = $this->repo->find($task['id']);
                $this->repo->save($originalTask, ['absolute_day' => $absolute_day, 'name' => $task['name']]);
                $absolute_day++;
            }
            if ($task['type'] == ProjectTask::STORY_TYPE) {
                $originalTask = $this->repo->find($task['id']);
                $take_days = $originalTask->story->take_days;
                $total = $originalTask->id + $take_days;
                for ($task['id']; $task['id'] < $total; $task['id']++) {
                    $ids[] = $task['id'];
                }
                $originalTasks = $this->repo->find($ids);

                foreach ($originalTasks as $task) {
                    $this->repo->save($task, ['absolute_day' => $absolute_day]);
                    $absolute_day++;
                }
            }
        }
    }
}
