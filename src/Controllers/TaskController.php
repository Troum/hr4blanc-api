<?php

namespace App\Controllers;

use App\Services\TaskService;
use App\Responses\Response;

class TaskController {
    /**
     * @var TaskService
     */
    protected TaskService $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService) {
        $this->taskService = $taskService;
    }

    /**
     * @return void
     */
    public function getTasks(): void
    {
        $tasks = $this->taskService->getTasks();
        Response::make()->json(['items' => $tasks])->send();
    }

    /**
     * @param int $id
     * @return void
     */
    public function getTask(int $id): void
    {
        $task = $this->taskService->getTaskById($id);
        if ($task) {
            Response::make()->json(['item' => $task])->send();
        } else {
            Response::make()->json(['error' => 'Task not Found'])->status(404)->send();
        }
    }
}
