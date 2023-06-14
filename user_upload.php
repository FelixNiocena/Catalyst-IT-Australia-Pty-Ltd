<?php

require __DIR__ . '/database/createDatabase.php';
$config = require __DIR__ . '/config/config.php';

$createDatabase = new CreateDatabase($config);

try {
    $createDatabase->createConnection();
    $createDatabase->createUserTable();
} catch (Exception $e) {
    echo 'An error occurred: ' . $e->getMessage();
}
