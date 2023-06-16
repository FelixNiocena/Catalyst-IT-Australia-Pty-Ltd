<?php


class UserUploadHelper
{
    protected $databaseName;

    public function helperOption($argv)
    {
        /*Gets information about script --help*/
        $help = in_array('--help', $argv);
        if ($help) {
            echo "--file [csv file name] – process .csv to be stored into mysql\n";
            echo "--create-database - creates database based on config.php file\n";
            echo "--create_table – build users table \n";
            echo "--dry_run – potential rows to be inserted\n";
            echo "-u – MySQL username\n";
            echo "-p – MySQL password\n";
            echo "-h – MySQL host\n";
            echo "--help – which will output the above list of directives with details.\n";
        }
    }

    public function createDatabase($dbName, $argv, $argc, $importDatabaseClass)
    {
        $createDb = in_array('--create-database', $argv);
        $username = null;
        $password = null;
        $host = null;

        // Pass additional parameters
        for ($i = 2; $i < $argc; $i++) {
            switch ($argv[$i]) {
                case '-u':
                    if ($i + 1 < $argc) {
                        $username = $argv[$i + 1]; // Assign the value to $username
                        $i++; // Skip the next argument
                    } else {
                        echo "Please provide a MySQL username after the -u option.\n";
                        exit(1);
                    }
                    break;
                case '-p':
                    if ($i + 1 < $argc) {
                        $password = $argv[$i + 1]; // Assign the value to $password
                        $i++; // Skip the next argument
                    } else {
                        echo "Please provide a MySQL password after the -p option.\n";
                        exit(1);
                    }
                    break;
                case '-h':
                    if ($i + 1 < $argc) {
                        $host = $argv[$i + 1]; // Assign the value to $host
                        $i++; // Skip the next argument
                    } else {
                        echo "Please provide a MySQL host after the -h option.\n";
                        exit(1);
                    }
                    break;
                default:
                    echo "Unknown option: " . $argv[$i] . "\n";
                    exit(1);
            }
        }
        if ($createDb) {
            if (!$username || !$password || !$host) {
                echo "Please provide the required options: -u [MySQL username] -p [MySQL password] -h [MySQL host]\n";
                exit(1);
            }
            $importDatabaseClass->createConnection($host, $username, $password, $dbName);
            exit;
        }
    }

    public function createTable($dbName, $argc, $argv, $importDatabaseClass)
    {
        $createTable = in_array('--create-table', $argv);
        $username = null;
        $password = null;
        $host = null;
        // Pass additional parameters
        for ($i = 2; $i < $argc; $i++) {
            switch ($argv[$i]) {
                case '-u':
                    if ($i + 1 < $argc) {
                        $username = $argv[$i + 1]; // Assign the value to $username
                        $i++; // Skip the next argument
                    } else {
                        echo "Please provide a MySQL username after the -u option.\n";
                        exit(1);
                    }
                    break;
                case '-p':
                    if ($i + 1 < $argc) {
                        $password = $argv[$i + 1]; // Assign the value to $password
                        $i++; // Skip the next argument
                    } else {
                        echo "Please provide a MySQL password after the -p option.\n";
                        exit(1);
                    }
                    break;
                case '-h':
                    if ($i + 1 < $argc) {
                        $host = $argv[$i + 1]; // Assign the value to $host
                        $i++; // Skip the next argument
                    } else {
                        echo "Please provide a MySQL host after the -h option.\n";
                        exit(1);
                    }
                    break;
                default:
                    echo "Unknown option: " . $argv[$i] . "\n";
                    exit(1);
            }
        }
        /*Creating table --create-table*/
        if ($createTable) {
            if (!$username || !$password || !$host) {
                echo "Please provide the required options: -u [MySQL username] -p [MySQL password] -h [MySQL host]\n";
                exit(1);
            }
            $importDatabaseClass->createUserTable($host, $username, $password, $dbName);
            exit;
        }
    }

    public function importDb($dbName, $argc, $argv, $importUserClass)
    {
        /*Import CSV to SQL --file */
        $file = in_array('--file', $argv);
        if ($file) {
            if ($argc < 9 || $argv[1] !== '--file') {
                echo "Usage: php user_upload.php --file [csv file name] -u [MySQL username] -p [MySQL password] -h [MySQL host]\n";
                exit(1);
            }

            $csvFile = $argv[2]; // Retrieve the CSV file name from the command-line argument
            $username = null;
            $password = null;
            $host = null;

            // Parse additional parameters
            for ($i = 3; $i < $argc; $i++) {
                switch ($argv[$i]) {
                    case '-u':
                        if ($i + 1 < $argc) {
                            $username = $argv[$i + 1]; // Assign the value to $username
                            $i++; // Skip the next argument
                        } else {
                            echo "Please provide a MySQL username after the -u option.\n";
                            exit(1);
                        }
                        break;
                    case '-p':
                        if ($i + 1 < $argc) {
                            $password = $argv[$i + 1]; // Assign the value to $password
                            $i++; // Skip the next argument
                        } else {
                            echo "Please provide a MySQL password after the -p option.\n";
                            exit(1);
                        }
                        break;
                    case '-h':
                        if ($i + 1 < $argc) {
                            $host = $argv[$i + 1]; // Assign the value to $host
                            $i++; // Skip the next argument
                        } else {
                            echo "Please provide a MySQL host after the -h option.\n";
                            exit(1);
                        }
                        break;
                    default:
                        echo "Unknown option: " . $argv[$i] . "\n";
                        exit(1);
                }
            }

            if (!file_exists($csvFile)) {
                echo "The specified CSV file does not exist.\n";
                exit(1);
            }

            $handle = fopen($csvFile, 'r');
            if ($handle === false) {
                echo "Unable to open the CSV file.\n";
                exit(1);
            }

            $importUserClass->importCsv($host, $username, $password, $dbName, $handle, false);
            exit(1);
        }
    }

    public function importDbDryRun($dbName, $argc, $argv, $importUserClass)
    {
        /*Perform dry run --dry-run*/
        $dryRun = in_array('--dry-run', $argv);
        $username = null;
        $password = null;
        $host = null;

        if ($dryRun) {
            if ($argc < 3 || $argv[1] !== '--dry-run') {
                echo "Please provide the CSV file.\n";
                exit(1);
            }
            $csvFile = $argv[2]; // Get CSV File from commandline

            if (!file_exists($csvFile)) {
                echo "The specified CSV file does not exist.\n";
                exit(1);
            }

            $handle = fopen($csvFile, 'r');
            if ($handle === false) {
                echo "Unable to open the CSV file.\n";
                exit(1);
            }
            $importUserClass->importCsv($host, $username, $password, $dbName, $handle, true);
            exit(1);
        }
    }
}
