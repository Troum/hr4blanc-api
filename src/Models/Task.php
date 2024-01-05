<?php

namespace App\Models;

class Task extends BaseModel {
    /**
     * @var string
     */
    protected static string $table = 'tasks';

    /**
     * @var array|string[]
     */
    protected array $fields = ['id', 'title', 'date', 'author', 'status', 'description'];
}
