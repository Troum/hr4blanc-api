<?php

ob_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use App\Middlewares\CORSMiddleware;
use App\Providers\RouteServiceProvider;

$corsMiddleware = new CORSMiddleware();
$corsMiddleware->handle();

RouteServiceProvider::boot($container);

ob_end_flush();
