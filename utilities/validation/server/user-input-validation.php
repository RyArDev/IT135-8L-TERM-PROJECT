<?php

    require_once('utilities/database/db-controller.php');
    require_once('entities/user/user-model.php');
    require_once('plugins/html-purifier.php');

    function validateUserRegistration(UserRegister $userRegister){

        $errors = array();

        if(!validateUserUsername("register", $userRegister->username)){

            $errors[] = "Invalid Username (4 - 16 characters).";

        }

        if(!validateUserFirstName($userRegister->firstName)){

            $errors[] = "Invalid First Name (2 - 48 characters).";

        }  
        
        if(!validateUserLastName($userRegister->lastName)){

            $errors[] = "Invalid Last Name (2 - 48 characters).";

        }

        if(!validateUserEmail("register", $userRegister->email)){

            $errors[] = "Invalid Email.";

        }

        if(!validateUserPassword($userRegister->password, $userRegister->confirmPassword)){

            $errors[] = "Invalid Password.";

        }

        $userRegister->password = hashUserPasswordWithSalt($userRegister->password);

        if(!validateUserBirthDate($userRegister->birthDate)){

            $errors[] = "Invalid Birth Date.";

        }

        if(!validateUserAddress($userRegister->address)){

            $errors[] = "Invalid Home Address (12 - 128 characters).";

        }

        if(!validateUserGender($userRegister->gender)){

            $errors[] = "Gender must be either 'Male', 'Female', or 'Other'";

        }

        if(!validateUserAgreedToTerms($userRegister->agreeTerms)){

            $errors[] = "Read then Agree to the Terms and Conditions.";

        }

        return $errors;
    
    }
    
    function validateUserLogin(UserLogin $userLogin){

        $errors = array();

        //Query the result from the database to get the hashed password
        $dbPassword = null;

        if(isset(getUserPasswordByUsername($userLogin->username)['password'])){

            $dbPassword = getUserPasswordByUsername($userLogin->username)['password'];

        }

        if(!validateUserUsername("login", $userLogin->username) || !verifyUserPasswordWithSalt($userLogin->password, $dbPassword)){

            $errors[] = "Invalid Credentials.";

        }else{

            $userLogin->password = $dbPassword;

        }

        return $errors;
    
    }

    function validateUser(UserEdit $userEdit){

        $errors = array();

        if(!validateUserUsername($userEdit->userId, $userEdit->username)){

            $errors[] = "Invalid or Taken Username (4 - 16 characters).";

        }

        if(!validateUserEmail($userEdit->userId, $userEdit->email)){

            $errors[] = "Invalid or Taken Email.";

        }

        return $errors;

    }

    function validateAdminUser(UserAdminEdit $userEdit){

        $errors = array();

        if(!validateUserUsername($userEdit->userId, $userEdit->username)){

            $errors[] = "Invalid or Taken Username (4 - 16 characters).";

        }

        if(!validateUserEmail($userEdit->userId, $userEdit->email)){

            $errors[] = "Invalid or Taken Email.";

        }

        if(!validateUserRoles($userEdit->roleId)){

            $errors[] = "Invalid Role.";

        }

        return $errors;

    }

    function validateUserProfile(UserProfileEdit $userEditProfile){

        $errors = array();

        if(!validateUserFirstName($userEditProfile->firstName)){

            $errors[] = "Invalid First Name (2 - 48 characters).";

        }

        if(!validateUserLastName($userEditProfile->lastName)){

            $errors[] = "Invalid Last Name (2 - 48 characters).";

        }

        if(!validateUserBirthDate($userEditProfile->birthDate)){

            $errors[] = "Invalid Birth Date.";

        }

        if(!validateUserAddress($userEditProfile->address)){

            $errors[] = "Invalid Home Address (12 - 128 characters).";

        }

        if(!validateUserGender($userEditProfile->gender)){

            $errors[] = "Gender must be either 'Male', 'Female', or 'Other'";

        }

        if($userEditProfile->phoneNumber !== "0" && !validateUserPhoneNumber($userEditProfile->userId, $userEditProfile->phoneNumber)){
         
            $errors[] = "Invalid or Taken Phone Number (7-15 digits).";
            
        }

        if(!validateUserJobTitle($userEditProfile->jobTitle)){

            $errors[] = "Invalid Job Title (4 - 64 characters).";

        }

        if(!validateUserJobDescription($userEditProfile->jobDescription)){

            $errors[] = "Invalid Job Description (12 - 500 characters).";

        }

        if(!validateUserDescription($userEditProfile->description)){

            $errors[] = "Invalid User Description (12 - 2500 characters).";

        }

        return $errors;

    }

    function validateUserEditPassword(UserEditPassword $userEditPassword, $role){

        $errors = array();
        $role = strtolower($role);

        // Check if any field is empty
        if (empty($userEditPassword->oldPassword) || empty($userEditPassword->newPassword) || empty($userEditPassword->confirmNewPassword)) {
            
            $errors[] = "All fields are required.";
        
        }

        //Query the result from the database to get the hashed password
        $dbPassword = null;

        if(isset(getUserPasswordById($userEditPassword->userId)['password']) && $role !== 'admin'){

            $dbPassword = getUserPasswordById($userEditPassword->userId)['password'];

        }

        // Check if the old password match from the database password
        if (!verifyUserPasswordWithSalt($userEditPassword->oldPassword, $dbPassword) && $role !== 'admin') {
            
            $errors[] = "Old Password does not match to your current Password.";
        
        }

        // Check if the new password and confirm new password match
        if ($userEditPassword->newPassword !== $userEditPassword->confirmNewPassword) {
            
            $errors[] = "New Password and Confirm New Password do not match.";
        
        }

        // Check if the new password is the same as the old password
        if ($userEditPassword->oldPassword === $userEditPassword->newPassword) {
            
            $errors[] = "New Password cannot be the same as the Old Password.";
        
        }

        // Check if the new password contains at least one number
        if (!preg_match('/\d/', $userEditPassword->newPassword)) {
            
            $errors[] = "New Password must contain at least one number.";
       
        }

        // Check if the new password contains at least one special character
        if (!preg_match('/[\W_]/', $userEditPassword->newPassword)) {
            
            $errors[] = "New Password must contain at least one special character.";
        
        }

        // Check if the new password length is between 8 and 64 characters
        $newPasswordLength = strlen($userEditPassword->newPassword);
        if ($newPasswordLength < 9) {
            
            $errors[] = "New Password must be more than 8 characters long.";
        
        }

        $userEditPassword->newPassword = hashUserPasswordWithSalt($userEditPassword->newPassword);

        return $errors;

    }

    function sanitizeUserClass($object){
    
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

    function sanitizeUserInput($input){

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

    function hashUserPasswordWithSalt($password) {

        // Generate a random salt
        $salt = bin2hex(random_bytes(16)); // 16 bytes = 128 bits

        // Combine the password and salt
        $passwordWithSalt = $password . $salt;
        
        // Hash the combined string using SHA256
        $hashedPassword = hash('sha256', $passwordWithSalt);
    
        return $salt.":".$hashedPassword;
    }

    function verifyUserPasswordWithSalt($password, $dbPassword) {

        if($dbPassword == null){

            return false;

        }

        // Combine the password and salt
        list($salt, $hashedPassword) = explode(':', $dbPassword);

        $passwordWithSalt = hash('sha256', $password . $salt);
        
        // Compare the hashed entered password with the stored hashed password
        return $passwordWithSalt === $hashedPassword;

    }

    function validateUserUsername($userId, $username){

        if(!ctype_digit($userId)){

            if($userId == "login"){

                return true;

            }

            $userId = null;

        }

        if(verifyUserUsername($userId, $username)['username_count'] > 0){

            return false;

        }

        // Username pattern: only allow alphanumeric characters and underscores, 4 to 16 characters long
        return preg_match('/^[a-zA-Z0-9_]{4,16}$/', $username);

    }

    function validateUserFirstName($firstName){

        // Name pattern: only allow letters and spaces, 2 to 48 characters long
        return preg_match('/^[a-zA-Z\s]{2,48}$/', $firstName);
    }

    function validateUserLastName($lastName){

        // Name pattern: only allow letters, 2 to 48 characters long
        return preg_match('/^[a-zA-Z\s]{2,48}$/', $lastName);

    }

    function validateUserEmail($userId, $email){

        if(!ctype_digit($userId)){

            if($userId == "login"){

                return true;

            }

            $userId = null;

        }

        if(verifyUserEmail($userId, $email)['email_count'] > 0){

            return false;

        }

        // Maximum length of 5 - 64 characters
        if (strlen($email) < 5 || strlen($email) > 64) {

            return false;

        }

        // Email pattern: use a basic email validation regex
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function validateUserPassword($password, $confirmPassword){

        // Check if both fields are not empty
        if (empty($password) || empty($confirmPassword)) {

            return false;

        }

        // Check if the password and confirm password match
        if ($password !== $confirmPassword) {

            return false;

        }

        // Check if the password contains at least one number
        if (!preg_match('/\d/', $password)) {

            return false;

        }

        // Check if the password contains at least one special character
        if (!preg_match('/[\W_]/', $password)) {

            return false;

        }

        // Check if the password length is between 8 and 64 characters
        $passwordLength = strlen($password);

        if ($passwordLength < 9) {

            return false;

        }

        // If all checks pass, the password is valid
        return true;
    }

    function validateUserBirthDate($birthDate){
        
        // Birth date pattern: use a basic date format (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthDate)) {

            return false;

        }
    
        // Check if the birth date is valid and not in the future
        $currentDate = new DateTime();
        $inputDate = DateTime::createFromFormat('Y-m-d', $birthDate);
    
        if (!$inputDate || $inputDate > $currentDate) {

            return false;

        }
    
        // Calculate the age based on the birth date
        $dateDiff = $inputDate->diff($currentDate);
        $age = $dateDiff->y;
    
        // Ensure that the age is not less than 13 years
        if ($age < 13) {

            return false;

        }
    
        return true;

    }

    function validateUserAddress($address){

        // Check if the address length is between 12 and 128 characters
        $addressLength = strlen($address);
        
        if ($addressLength < 12 || $addressLength > 128) {

            return false;

        }

        // Split the address into its individual parts
        $addressParts = explode(', ', $address);

        // Check if all the required address parts are present
        if (count($addressParts) < 4) {

            return false;

        }

        if(count($addressParts) !== 5){

            list($address1, $city, $state, $zip) = explode(', ', $address);

        }else{

            list($address1, $address2, $city, $state, $zip) = explode(', ', $address);

        }

        // Check if any part is empty (optional parts)
        if (empty($address1) || empty($city) || empty($state) || empty($zip)) {

            return false;

        }

        // Additional validation for zip code: Check if it contains only digits
        if (!ctype_digit($zip)) {

            return false;
            
        }

        return true;

    }

    function validateUserGender($gender){

        // List of valid gender options
        $validGenders = array('Male', 'Female', 'Other');

        // Check if the provided gender is in the list of valid options
        if (!in_array($gender, $validGenders)) {

            return false;

        }

        // If the gender is valid, return an empty string
        return true;
    }

    function validateUserPhoneNumber($userId, $phoneNumber){

        if(!ctype_digit($userId)){

            if($userId == "login"){

                return true;

            }

            $userId = null;

        }

        if($phoneNumber === null){

            return true;

        }

        if(verifyUserPhoneNumber($userId, $phoneNumber)['phone_number_count'] > 0){

            return false;

        }

        // Phone number pattern: only allow numbers, 7 to 15 characters long
        return preg_match('/^[0-9]{7,15}$/', $phoneNumber);
        
    }

    function validateUserJobTitle($jobTitle){

        if(empty($jobTitle)){

            return true;

        }

        // Maximum length of 4 - 64 characters
        if (strlen($jobTitle) < 4 || strlen($jobTitle) > 64) {

            return false;

        }

        // Job title pattern: allow letters, numbers, spaces, and common special characters, 2 to 32 characters long
        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/{}[\]]+$/', $jobTitle);

    }

    function validateUserJobDescription($jobDescription){

        if(empty($jobDescription)){

            return true;

        }

        // Maximum length of 12 - 2500 characters
        if (strlen($jobDescription) < 12 || strlen($jobDescription) > 2500) {

            return false;

        }

        $jobDescription = cleanHtml($jobDescription);
        
        // Job description pattern: allow letters, numbers, spaces, and common special characters, 2 to 64 characters long
        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/{}[\]]+$/', $jobDescription);

    }

    function validateUserDescription($userDescription){

        if(empty($userDescription)){

            return true;

        }

        // Maximum length of 12 - 2500 characters
        if (strlen($userDescription) < 12 || strlen($userDescription) > 2500) {

            return false;

        }

        $userDescription = cleanHtml($userDescription);
        
        // Job description pattern: allow letters, numbers, spaces, and common special characters, 2 to 64 characters long
        return preg_match('/^[a-zA-Z0-9\s\-.,\'":;!()@#$%^&*_+=<>?\/{}[\]]+$/', $userDescription);

    }

    function validateUserRoles($id) {
        // Convert the input to an integer
        $id = (int)$id;
    
        // Define the valid options for the select element
        $validOptions = [1, 2, 3, 4];
    
        // Check if the input is one of the valid options
        return in_array($id, $validOptions);
    }

    function validateUserAgreedToTerms($agreeTerms) {

        if(empty($agreeTerms)){

            return false;

        }

        return true;

    }


?>