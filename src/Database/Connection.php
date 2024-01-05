<?php

namespace App\Database;

use PDO;

class Connection {

    /**
     * @return PDO
     */
    public static function connect(): PDO
    {
        $pdo = new PDO('sqlite:' . __DIR__ . '/../../database/database.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
