<?php
//Importing classes
require __DIR__ . '/database/Database.php';
require __DIR__ . '/database/ImportUsers.php';
$config = require __DIR__ . '/config/config.php';
$helper = require __DIR__ . '/app/code/Catalyst/helper/UserUploadHelper.php';

if ($argc < 2) {
    echo "Please provide at least one command-line option. Use --help for options\n";
    exit(1);
}

//Instantiating classes into values
$importDatabaseClass = new Database();
$importUserClass = new ImportUsers();
$helper = new UserUploadHelper();

//DB credentials
$dbName = $config['dbname'];

$helper->importDbDryRun($dbName, $argc, $argv, $importUserClass);
$helper->importDb($dbName, $argc, $argv, $importUserClass);
$helper->helperOption($argv);
$helper->createDatabase($dbName, $argv, $argc, $importDatabaseClass);
$helper->createTable($dbName, $argc, $argv, $importDatabaseClass);
