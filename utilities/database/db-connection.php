<?php
    require_once(dirname(dirname(__DIR__)).'/utilities/settings/stage-settings.php');

    $config = json_decode(file_get_contents(getConfigFile()), true);

    $type = $config['DATABASE']['Type'];
    $host = $config['DATABASE']['Host'];
    $port = $config['DATABASE']['Port'];
    $dbName = $config['DATABASE']['Name'];
    $username = $config['DATABASE']['User'];
    $password = $config['DATABASE']['Password'];

    try {

        // Connect to the database using MySQLi
        $dbConnection = new mysqli($host, $username, $password, $dbName, $port);

        // Check if the connection was successful
        if ($dbConnection->connect_errno) {

            echo "Database Connection Failed: " . $dbConnection->connect_error;
            return;

        }

        // Set the character set to utf8mb4
        if (!$dbConnection->set_charset("utf8mb4")) {

            echo "Error setting character set: " . $dbConnection->error;
            return;

        }

    } catch (Exception $e) {

        echo "Database Connection Failed: " . $e->getMessage();
        return;
        
    }
?>
