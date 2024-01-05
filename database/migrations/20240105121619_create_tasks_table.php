<?php

return function (PDO $pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS tasks(
    
    id INTEGER PRIMARY KEY AUTOINCREMENT NULL,
            title TEXT NULL,
            date TEXT NULL,
            author TEXT NULL,
            status TEXT NULL,
            description TEXT NULL)"
    );
};