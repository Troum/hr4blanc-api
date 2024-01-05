<?php

require 'vendor/autoload.php';
use App\Database\Connection;

$pdo = Connection::connect();

$migrations = glob(__DIR__ . '/../database/migrations/*.php');

foreach ($migrations as $migration) {
    $migrationFunction = require $migration;
    $migrationFunction($pdo);
    echo "Ran: " . basename($migration) . PHP_EOL;
}
