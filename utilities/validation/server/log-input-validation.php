<?php 

    require_once('utilities/database/db-controller.php');
    require_once('entities/log/log-model.php');
    require_once('plugins/html-purifier.php');

    function validateEditLog(LogEdit $logEdit){

        $errors = array();

        if(!validateLogTableName($logEdit->tableName)){

            $errors[] = "Invalid Table Name (4 - 64 characters).";

        }  
        
        if(!validateLogDescription($logEdit->description)){

            $errors[] = "Invalid description (12 - 5000 characters and JSON Format).";

        }

        return $errors;
    
    }

    function sanitizeLogClass($object){
    
        if (!is_object($object)) {

            throw new InvalidArgumentException("Input must be an object.");

        }

        $reflection = new ReflectionClass($object);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {

                $propertyName = $property->getName();
                $property->setAccessible(true);
                $propertyValue = $property->getValue($object);

                if (is_string($propertyValue)) {

                    // For strings, remove leading and trailing white spaces, remove backslashes, and convert special characters to HTML entities
                    $propertyValue = trim($propertyValue);
                    $propertyValue = stripslashes($propertyValue);
                    $propertyValue = htmlspecialchars($propertyValue, ENT_QUOTES);
                
                } elseif (is_numeric($propertyValue)) {
                    
                    // For numeric input, ensure it is converted to the correct numeric type
                    $propertyValue = filter_var($propertyValue, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
                } elseif (is_bool($propertyValue)) {

                    // For boolean input, convert it to a boolean value
                    $propertyValue = filter_var($propertyValue, FILTER_VALIDATE_BOOLEAN);
                
                }

                // Set the sanitized value back to the property
                $property->setValue($object, $propertyValue);
            }

        return $object;

    }

    function sanitizeLogInput($input){

        // Sanitize different types of input based on their data type
        if (is_string($input)) {

            // For strings, remove leading and trailing white spaces and convert special characters to HTML entities
            $input = trim($input);
            $input = htmlspecialchars($input, ENT_QUOTES);

        } elseif (is_numeric($input)) {

            // For numeric input, ensure it is converted to the correct numeric type
            $input = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        } elseif (is_bool($input)) {

            // For boolean input, convert it to a boolean value
            $input = filter_var($input, FILTER_VALIDATE_BOOLEAN);

        }
        return $input;
    }

    function validateLogTableName($tableName){

        if(empty($tableName) || strlen($tableName) < 4 || strlen($tableName) > 64 ){

            return false;

        }

        $tableName = cleanHtml($tableName);

        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/{}[\]]+$/', $tableName);

    }

    function validateLogDescription($description){

        if(empty($description) || strlen($description) < 12 || strlen($description) > 5000 ){

            return false;

        }

        $decodedJson = json_decode($description);
        
        if ($decodedJson === null && json_last_error() !== JSON_ERROR_NONE) {

            return false;

        }

        $description = cleanHtml($description);

        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/{}[\]]+$/', $description);

    }


?>