<?php

class ImportUsers
{
    public function importCsv($host, $user, $pass, $table, $handle, $dryRun)
    {
        $conn = null;
        if (!$dryRun) {
            $conn = new mysqli($host, $user, $pass, $table);
            if ($conn->connect_error) {
                echo "Database connection failed: " . $conn->connect_error . "\n";
                exit(1);
            }
        }

        $rowNumber = 0;
        $potentialRows = [];

        while (($data = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if ($rowNumber === 1) {
                continue; // Skip the column name
            }

            $name = ucfirst(strtolower($data[0])); // Safe use in SQL query
            $surname = ucfirst(strtolower($data[1])); // Safe use in SQL query
            $email = strtolower($data[2]); // Safe use in SQL query

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            if ($dryRun) {
                $potentialRows[] = "Potential row: $name, $surname, $email";
            } else {
                $escapedName = $conn->real_escape_string($name);
                $escapedSurname = $conn->real_escape_string($surname);
                $escapedEmail = $conn->real_escape_string($email);

                $checkQuery = "SELECT email FROM users WHERE email = '$escapedEmail'";
                $checkResult = $conn->query($checkQuery);

                if ($checkResult->num_rows === 0) {
                    $sql = "INSERT IGNORE INTO users (name, surname, email) VALUES ('$escapedName', '$escapedSurname', '$escapedEmail')";
                    if ($conn->query($sql) === true) {
                        echo "Inserted row: $name, $surname, $email\n";
                    } else {
                        echo "Error inserting row: " . $conn->error . "\n";
                    }
                } else {
                    echo "Skipped existing record: $name, $surname, $email\n";
                }
            }
        }

        if ($dryRun) {
            echo implode("\n", $potentialRows) . "\n";
        }

        if ($conn !== null) {
            $conn->close();
        }

        fclose($handle);
    }
}
