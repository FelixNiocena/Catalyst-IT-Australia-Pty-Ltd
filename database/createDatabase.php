<?php

$config = require __DIR__ . '/../config/config.php';

class CreateDatabase
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function createConnection()
    {
        $host = $this->config['host'];
        $dbName = $this->config['dbname'];
        $user = $this->config['username'];
        $pass = $this->config['password'];

        // Establishing connection
        try {
            $conn = new mysqli($host, $user, $pass);

            // Check if the database already exists
            if ($this->databaseExists($conn, $dbName)) {
                echo 'Database exists: ' . ucfirst(strtolower($dbName)) . "\n";
                $conn->close();
                return;
            }

            // Creating database
            $createDbScript = 'CREATE DATABASE ' . $dbName;
            $conn->query($createDbScript);
            echo 'Created database: ' . ucfirst(strtolower($dbName)) . ' successfully' . "\n";
            $conn->close();

        } catch (Exception $e) {
            die("Something went wrong: " . $e);
        }
    }

    public function createUserTable()
    {
        $host = $this->config['host'];
        $dbName = $this->config['dbname'];
        $user = $this->config['username'];
        $pass = $this->config['password'];
        $tableName = 'users';

        try {
            $conn = new mysqli($host, $user, $pass);
            $conn->select_db($dbName);

            // Check if table already exists
            if ($this->tableExists($conn, $tableName)) {
                echo 'Table exists: ' . ucfirst(strtolower($tableName));
                $conn->close();
                return;
            }

            $script = "CREATE TABLE $tableName (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(30) NOT NULL,
                surname VARCHAR(30) NOT NULL,
                email VARCHAR(50))";

            $conn->query($script);
            echo 'Successfully created users table';
            $conn->close();

        } catch (Exception $e) {
            die("Error creating table: " . $e);
        }
    }

    private function databaseExists($conn, $dbName)
    {
        $result = $conn->query("SHOW DATABASES LIKE '$dbName'");
        return $result->num_rows > 0;
    }

    private function tableExists($conn, $tableName)
    {
        $result = $conn->query("SHOW TABLES LIKE '$tableName'");
        return $result->num_rows > 0;
    }
}

