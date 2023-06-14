<?php

class ImportUsers
{
    public function importCsv($host, $user, $pass, $table, $handle)
    {

        $conn = new mysqli($host, $user, $pass, $table);
        if ($conn->connect_error) {
            echo "Database connection failed: " . $conn->connect_error . "\n";
            exit(1);
        }

        $rowNumber = 0;
        // Read and process the CSV data
        while (($data = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if ($rowNumber === 1) {
                continue; // Skip the column name
            }
            $name = ucfirst(strtolower($conn->real_escape_string($data[0]))); // Escape the value in the first column for safe use in SQL query
            $surname = ucfirst(strtolower($conn->real_escape_string($data[1]))); // Escape the value in the second column for safe use in SQL query
            $email = strtolower($conn->real_escape_string($data[2])); // Escape the value in the third column for safe use in SQL query

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            // Create the SQL insert statement
            $sql = "INSERT INTO users (name, surname, email) VALUES ('$name', '$surname', '$email')";

            // Execute the SQL statement
            if ($conn->query($sql) === true) {
                echo "Inserted row: $name, $surname, $email\n";
            } else {
                echo "Error inserting row: " . $conn->error . "\n";
            }
        }

        fclose($handle);
    }
}
