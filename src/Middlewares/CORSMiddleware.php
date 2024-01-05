<?php

namespace App\Middlewares;

class CORSMiddleware
{
    /**
     * @return void
     */
    public function handle(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }
    }
}