<?php

if ($argc < 2) {
    echo "Error: Migration name argument is required." . PHP_EOL;
    exit(1);
}

$migrationName = $argv[1];
$timestamp = date('YmdHis');
$filename = $timestamp . '_' . $migrationName . '.php';
$filepath = __DIR__ . '/../database/migrations/' . $filename;
$tableName = explode('_', $migrationName)[1];
$template = <<<EOT
<?php

return function (\PDO \$pdo) {
    \$pdo->exec("CREATE TABLE IF NOT EXISTS $tableName()");
};
EOT;

file_put_contents($filepath, $template);
echo "Migration created at: $filepath" . PHP_EOL;
