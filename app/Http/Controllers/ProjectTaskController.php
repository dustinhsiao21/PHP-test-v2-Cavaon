<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProjectTaskService;

class ProjectTaskController extends Controller
{
    private $service;

    public function __construct(ProjectTaskService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return view('project/index');
    }

    public function apiGetTasks()
    {
        return $this->service->getProjectOneTasks();
    }

    public function apiUpdateTasks(Request $request)
    {
        $request = $request->get('tasks');
        $this->service->updateTasks($request);
    }
}
