<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    /**
     * @param int $num
     * @return array
     */
    public function getTasks(int $num = 1000): array {
        if (empty(Task::all())) {
            for ($i = 1; $i <= $num; $i++) {
                Task::create([
                    'title' => 'Task ' . $i,
                    'date' => date('Y-m-d', strtotime("+$i days")),
                    'author' => 'Author ' . $i,
                    'status' => 'Status ' . $i,
                    'description' => 'Description: lorem ipsum ' . $i
                ]);
            }
            return Task::all();
        }
        return Task::all();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getTaskById(int $id): mixed
    {
        return Task::find($id);
    }
}