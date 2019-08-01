<?php

namespace App\Repositories;

use App\Models\ProjectTask;

class ProjectTaskRepository {
    
    private $model;

    public function __construct(ProjectTask $model)
    {
        $this->model = $model;
    }

    public function getProjectTasks(int $id)
    {
        return $this->model->where('project_id', $id);
    }

    /**
     * find data
     *
     * @param int|array $id
     * @return Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * save data
     *
     * @param ProjectTask $task
     * @param array $array
     * @return void
     */
    public function save(ProjectTask $task, array $array)
    {
        $task->fill($array);
        $task->save();
    }
}