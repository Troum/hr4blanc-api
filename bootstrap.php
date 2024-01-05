<?php

use App\Containers\ApplicationContainer;
use App\Services\TaskService;

$container = new ApplicationContainer();
$container->bind(TaskService::class);
