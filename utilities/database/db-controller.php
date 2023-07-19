<?php
    require_once('utilities/error/controller-error-handler.php');

    function executeQuery($query, $params = []) {
        
        try {

            // Requires Database connection
            require('utilities/database/db-connection.php');
    
            // Start a transaction
            mysqli_begin_transaction($dbConnection, MYSQLI_TRANS_START_READ_WRITE);
    
            // Prepare the statement
            $statement = $dbConnection->prepare($query);
    
            // Bind parameters, if any
            if (!empty($params)) {

                $paramTypes = '';
                $paramValues = [];
    
                foreach ($params as $param) {

                    if ($param instanceof DateTime) {

                        $paramTypes .= 's';
                        $paramValues[] = $param->format('Y-m-d H:i:s');

                    } else {

                        // Assuming all other parameters are strings, replace with appropriate types
                        $paramTypes .= 's';
                        $paramValues[] = $param;

                    }

                }
    
                // Use the spread operator to pass individual values to bind_param
                $statement->bind_param($paramTypes, ...$paramValues);

            }
    
            // Execute the statement
            $statement->execute();

            // Check if the statement returned any result set
            if ($statement->field_count === 0) {

                // No result set, return true to indicate successful execution
                mysqli_commit($dbConnection);
                $dbConnection->close();
                return true;

            }
    
            // Commit the transaction
            mysqli_commit($dbConnection);
    
            // Fetch the data, if any
            $result = $statement->get_result();
    
            // Close the connection
            $dbConnection->close();
    
            // Return the fetched data
            return $result->fetch_all(MYSQLI_ASSOC);
    
        } catch (Exception $e) {

            // Rollback the transaction if an error occurs
            mysqli_rollback($dbConnection);
    
            echo "Error Executing Query: " . $e->getMessage();
            return;

        }

    }

    function executeStoredProcedure($procedureName, $params = []) {
        try {
            // Requires Database connection
            require('utilities/database/db-connection.php');
    
            // Start a transaction
            mysqli_begin_transaction($dbConnection, MYSQLI_TRANS_START_READ_WRITE);
    
            // Prepare the statement
            $paramPlaceholders = implode(',', array_fill(0, count($params), '?'));
            $query = "CALL $procedureName($paramPlaceholders)";
            $statement = $dbConnection->prepare($query);
    
            // Bind parameters, if any
            if (!empty($params)) {

                $paramTypes = '';
                $paramValues = [];

                foreach ($params as $param) {

                    if ($param instanceof DateTime) {

                        $paramTypes .= 's';
                        $paramValues[] = $param->format('Y-m-d H:i:s');

                    } else {

                        // Assuming all other parameters are strings, replace with appropriate types
                        $paramTypes .= 's';
                        $paramValues[] = $param;

                    }

                }

                // Use the spread operator to pass individual values to bind_param
                $statement->bind_param($paramTypes, ...$paramValues);

            }

            // Execute the statement
            $statement->execute();

            // Check if the statement returned any result set
            if ($statement->field_count === 0) {
                // No result set, return true to indicate successful execution
                mysqli_commit($dbConnection);
                $dbConnection->close();
                return true;
            }

    
            // Fetch all result sets (if any) and store them in an array
            $resultSets = [];
            do {

                if ($result = $statement->get_result()) {

                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free_result();
                    $resultSets[] = $data;

                }

            } while ($statement->more_results() && $statement->next_result());

            // Commit the transaction
            mysqli_commit($dbConnection);

            // Close the connection
            $dbConnection->close();

            // Return the fetched data from all result sets
            return $resultSets;
    
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            mysqli_rollback($dbConnection);
    
            echo "Error Executing Stored Procedure: " . $e->getMessage();
            return;
        }
    }
    
?>
