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

    /**
     * get page view.
     *
     * @return view
     */
    public function index()
    {
        return view('Project/index');
    }

    /**
     * get project's tasks.
     *
     * @param Request $request
     * @return array
     */
    public function apiGetTasks(Request $request)
    {
        return $this->service->getProjectTasks($request->id);
    }

    /**
     * update project's tasks.
     *
     * @param Request $request
     * @return array
     */
    public function apiUpdateTasks(Request $request)
    {
        $this->service->updateTasks($request->tasks);
    }
}
