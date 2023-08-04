<?php

    require_once('utilities/database/db-controller.php');
    require_once('entities/forum/forum-model.php');
    require_once('entities/forum/forum-type-model.php');
    require_once('plugins/html-purifier.php');

    function validateAddForumType(ForumTypeCreate $forumTypeCreate){

        $errors = array();

        if(!validateForumType($forumTypeCreate->type)){

            $errors[] = "Invalid Topic (4 - 32 characters).";

        }

        return $errors;
    
    }

    function validateAddForumPost(ForumCreate $forumCreate){

        $errors = array();

        if(!validateForumTitle($forumCreate->title)){

            $errors[] = "Invalid Title (4 - 300 characters).";

        }  
        
        if(!validateForumBody($forumCreate->body)){

            $errors[] = "Invalid Body (12 - 5000 characters).";

        }

        return $errors;
    
    }

    function validateEditForumPost(ForumEdit $forumEdit){

        $errors = array();

        if(!validateForumTitle($forumEdit->title)){

            $errors[] = "Invalid Title (4 - 300 characters).";

        }  
        
        if(!validateForumBody($forumEdit->body)){

            $errors[] = "Invalid Body (12 - 5000 characters).";

        }

        return $errors;
    
    }

    function sanitizeForumClass($object){
    
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

    function sanitizeForumInput($input){

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

    function validateForumTitle($title){

        if(empty($title) || strlen($title) < 4 || strlen($title) > 300 ){

            return false;

        }

        $title = cleanHtml($title);

        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/{}[\]]+$/', $title);

    }

    function validateForumBody($body){

        if(empty($body) || strlen($body) < 12 || strlen($body) > 5000 ){

            return false;

        }

        $body = cleanHtml($body);

        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/{}[\]]+$/', $body);

    }

    function validateForumType($type) {
        
        if($type === "None" || empty($type) || $type === null){

            return false;

        }

        if(strlen($type) < 4 || strlen($type) > 32){

            return false;

        }

        return true;

    }

?>