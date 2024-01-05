<?php

use App\Router\Route;
use App\Controllers\TaskController;

Route::get('/api/v1/tasks', [TaskController::class, 'getTasks']);
Route::get('/api/v1/tasks/{id}', [TaskController::class, 'getTask']);