<?php 

    require_once(dirname(dirname(__DIR__)).'/utilities/database/db-controller.php');
    require_once(dirname(dirname(__DIR__)).'/entities/user/user-model.php');
    require_once(dirname(dirname(__DIR__)).'/utilities/error/controller-error-handler.php');

    function getUserById($userId){

        try {

            //Queries the results
            $params = [$userId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_GetById", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Getting User By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function getUserProfileByUserId($userId){

        try {

            //Queries the results
            $params = [$userId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_UserProfiles_GetByUserId", $params)[0];

            if(empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Getting User Profile By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function getUserLogin(UserLogin $user){

        try {

            //Queries the results
            $params = [$user->username, $user->password];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_Login", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Getting User By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function getUserPasswordById($userId){

        try {

            //Queries the results
            $params = [$userId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_GetPasswordById", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Getting Password By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function getUserPasswordByUsername($username){

        try {

            //Queries the results
            $params = [$username];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_GetPasswordByUsername", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Getting Password By Username Failed: " . $e->getMessage();
            return;

        }

    }

    function registerUser(UserRegister $user){

        try {

            // Prepare the query
            $params = [
                $user->username,
                $user->firstName,
                $user->lastName,
                $user->email,
                $user->password,
                $user->birthDate,
                $user->address,
                $user->gender,
                null,
                "Not Available",
                "Not Available",
                "Test123",
                new DateTime(),
                "default-profile-image",
                "assets/images/users/default-profile-image.webp",
                "default-banner-image",
                "assets/images/users/default-banner-image.webp",
                "Not Available",
                2
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_Register", $params);

            if (!$result) {

                return false;

            }

            return true;

        } catch (PDOException $e) {
            
            echo "Adding User To Database Failed: " . $e->getMessage();
            return;

        }

    }

    function updateUserStatusById($user_id, $status){

        try {

            //Queries the results
            $params = [$user_id, $status];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_UpdateStatusById", $params);

            if (!$result) {

                return false;

            }

            return true;

        } catch (PDOException $e) {
            
            echo "Updating User Status By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function updateUser(UserEdit $user){

        try{

            // Prepare the query
            $params = [
                $user->userId,
                $user->username,
                $user->email
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_UpdateInfoById", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (PDOException $e) {
            
            echo "Updating User Information Failed: " . $e->getMessage();
            return;

        }

    }

    function updateUserProfileByUserId(UserProfileEdit $userProfile){

        try{

            // Prepare the query
            $params = [
                $userProfile->firstName,
                $userProfile->lastName,
                $userProfile->birthDate,
                $userProfile->address,
                $userProfile->gender,
                $userProfile->phoneNumber,
                $userProfile->jobTitle,
                $userProfile->jobDescription,
                $userProfile->profileImageName,
                $userProfile->profileImageUrl,
                $userProfile->profileBannerName,
                $userProfile->profileBannerUrl,
                $userProfile->description,
                $userProfile->userId
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_UserProfiles_UpdateInfoByUserId", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (PDOException $e) {
            
            echo "Updating User Profile Information Failed: " . $e->getMessage();
            return;

        }

    }

    function updateUserPassword(UserEditPassword $user){

        try{
            // Prepare the query
            $params = [
                $user->userId,
                $user->newPassword
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_UpdatePasswordById", $params);

            if (!$result) {

                return false;

            }

            return true;

        } catch (PDOException $e) {
                
            echo "Updating User Password Failed: " . $e->getMessage();
            return;

        }

    }

    function verifyUserEmail($userId, $email){

        try {

            //Queries the results
            $params = [$userId, $email];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_VerifyDuplicateEmailById", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Verifying User Email Failed: " . $e->getMessage();
            return;

        }

    }

    function verifyUserUsername($userId, $username){

        try {

            //Queries the results
            $params = [$userId, $username];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Users_VerifyDuplicateUsernameById", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Verifying User Email Failed: " . $e->getMessage();
            return;

        }

    }

    function verifyUserPhoneNumber($userId, $phoneNumber){

        try {

            //Queries the results
            $params = [$userId, $phoneNumber];

            //Queries the results
            $result = executeStoredProcedure("WebApp_UserProfiles_VerifyDuplicatePhoneNumberByUserId", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (PDOException $e) {
            
            echo "Verifying User Email Failed: " . $e->getMessage();
            return;

        }

    }


?>