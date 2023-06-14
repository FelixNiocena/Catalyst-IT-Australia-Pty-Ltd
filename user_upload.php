<?php
//Importing classes
require __DIR__ . '/database/CreateDatabase.php';
require __DIR__ . '/database/ImportUsers.php';
$config = require __DIR__ . '/config/config.php';

//Instantiating classes into values
$createDatabase = new CreateDatabase($config);
$importUser = new ImportUsers();

//DB credentials
$host = $config['host'];
$dbName = $config['dbname'];
$user = $config['username'];
$pass = $config['password'];

/*Import CSV to SQL --file */
if ($argc < 3 || $argv[1] !== '--file') {
    echo "Please provide the CSV file using the --file option.\n";
    exit(1);
}

$csvFile = $argv[2]; // Retrieve the CSV file name from the command-line argument

if (!file_exists($csvFile)) {
    echo "The specified CSV file does not exist.\n";
    exit(1);
}

$handle = fopen($csvFile, 'r');
if ($handle === false) {
    echo "Unable to open the CSV file.\n";
    exit(1);
}

$importUser->importCsv($host, $user, $pass, $dbName, $handle);
