<?php

    require_once('utilities/database/db-controller.php');
    require_once('entities/user/user-model.php');
    require_once('plugins/html-purifier.php');

    function validateAddAnnouncement(AnnouncementCreate $announcementCreate){

        $errors = array();

        if(!validateTitle($announcementCreate->title)){

            $errors[] = "Invalid Title (4 - 300 characters).";

        }  
        
        if(!validateBody($announcementCreate->body)){

            $errors[] = "Invalid Body (12 - 5000 characters).";

        }

        if(!validateType($announcementCreate->announcementTypeId)){

            $errors[] = "Invalid Type (Please Select an Announcement Type).";

        }

        return $errors;
    
    }

    function validateEditAnnouncement(AnnouncementEdit $announcementEdit){

        $errors = array();

        if(!validateTitle($announcementEdit->title)){

            $errors[] = "Invalid Title (4 - 300 characters).";

        }  
        
        if(!validateBody($announcementEdit->body)){

            $errors[] = "Invalid Body (12 - 5000 characters).";

        }

        if(!validateType($announcementEdit->announcementTypeId)){

            $errors[] = "Invalid Type (Please Select an Announcement Type).";

        }

        return $errors;
    
    }

    function sanitizeClass($object){
    
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

    function sanitizeInput($input){

        // Sanitize different types of input based on their data type
        if (is_string($input)) {

            // For strings, remove leading and trailing white spaces, remove backslashes, and convert special characters to HTML entities
            $input = trim($input);
            $input = stripslashes($input);
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

    function validateTitle($title){

        if(empty($title) || strlen($title) < 4 || strlen($title) > 300 ){

            return false;

        }

        $title = cleanHtml($title);

        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/]+$/', $title);
    }

    function validateBody($body){

        if(empty($body) || strlen($body) < 12 || strlen($body) > 5000 ){

            return false;

        }

        $body = cleanHtml($body);

        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/]+$/', $body);

    }

    function validateType($type) {
        // Convert the input to an integer
        $type = (int)$type;
    
        // Define the valid options for the select element
        $validOptions = [1, 2, 3, 4, 5, 6];
    
        // Check if the input is one of the valid options
        return in_array($type, $validOptions);
    }


?>