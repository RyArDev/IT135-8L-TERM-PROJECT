<?php 
    
    require_once('entities/user/user-controller.php');
    require_once('utilities/error/controller-error-handler.php');
    require_once('plugins/jwt-token.php');

    function checkUserLogin() {
    
        try {

            $logCreate = new LogCreate();
            $logCreate->tableName = 'users';
            $logCreate->dateCreated = new DateTime();
            $logCreate->userId = null;

            if (!isset($_SESSION['user_id'])) {

                $userRefresh = checkUserRefreshToken($logCreate->description, $logCreate->userId);

                if($userRefresh !== null){
                    
                    createLog($logCreate);
                    $_SESSION['user_id'] = $userRefresh['user_id'];
                    return $userRefresh;
    
                }else{
    
                    return null;
    
                }

            }

            $activeStatus = updateUserStatusById($_SESSION['user_id'], 1);

            if(!$activeStatus){

                return null;

            }
 
            $user = getUserById($_SESSION['user_id']);

            switch($user['role_id']){

                case 1:{
                    $_SESSION['CKFinder_UserRole'] = 'none';
                    break;
                }
                case 2:{
                    $_SESSION['CKFinder_UserRole'] = 'user';
                    break;
                }
                case 3:{
                    $_SESSION['CKFinder_UserRole'] = 'officer';
                    break;
                }
                case 4:{
                    $_SESSION['CKFinder_UserRole'] = 'admin';
                    break;
                }
                default:{
                    $_SESSION['CKFinder_UserRole'] = 'none';
                    break;
                }

            }
            
            $_SESSION['jwt_token'] = generateJWTToken($user['user_id'], $user['username'], $user['email'], $user['role_id']);

            return $user;

        } catch (Exception $e) {
            
            echo "Checking User Login Failed: " . $e->getMessage();
            return;

        }
    
    }

    function checkUserProfile() {
    
        try {

            if (!isset($_SESSION['user_id'])) {

                return null;

            }

            return getUserProfileByUserId($_SESSION['user_id']);

        } catch (Exception $e) {
            
            echo "Checking User Profile Failed: " . $e->getMessage();
            return;

        }
    
    }

    function checkUserRefreshToken(&$logDescription, &$logUserId){

        try {

            if(!isset($_COOKIE['refresh_token'])){

                return null;

            }

            $refreshTokenLog = new LogUserRefreshToken();
            $refreshTokenLog->refreshToken = $_COOKIE['refresh_token'];
            $refreshTokenLog->userId = null;
            $refreshTokenLog->time = new DateTime();
            $refreshTokenLog->status = 'login by refresh token pending';
            $refreshTokenLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
            $refreshTokenLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
            $refreshTokenLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

            $refresh = verifyUserRefreshToken($_COOKIE['refresh_token']);

            if(empty($refresh)){

                $refreshTokenLog->status = 'login by refresh token failed';
                $logDescription = createLogUserRefreshToken($refreshTokenLog);
                return null;

            }
            
            $activeStatus = updateUserStatusById($refresh['user_id'], 1);

            if(!$activeStatus){

                $refreshTokenLog->status = 'login by refresh token failed';
                $logDescription = createLogUserRefreshToken($refreshTokenLog);
                return null;

            }
 
            $user = getUserById($refresh['user_id']);

            switch($user['role_id']){

                case 1:{
                    $_SESSION['CKFinder_UserRole'] = 'none';
                    break;
                }
                case 2:{
                    $_SESSION['CKFinder_UserRole'] = 'user';
                    break;
                }
                case 3:{
                    $_SESSION['CKFinder_UserRole'] = 'officer';
                    break;
                }
                case 4:{
                    $_SESSION['CKFinder_UserRole'] = 'admin';
                    break;
                }
                default:{
                    $_SESSION['CKFinder_UserRole'] = 'none';
                    break;
                }

            }

            $refreshTokenLog->userId = $user['user_id'];
            $logUserId = $user['user_id'];
            $refreshTokenLog->status = 'login by refresh token success';
            $logDescription = createLogUserRefreshToken($refreshTokenLog);

            return $user;

        } catch (Exception $e) {
            
            echo "Checking User Refresh Token Failed: " . $e->getMessage();
            return;

        }

    }

    function redirectToProfileIfLoggedIn() {

        if (isset($_SESSION['user_id']) && $_SESSION['current_page'] !== "profile") {
            
            header("Location: profile");
            exit();
        
        }
    }

?>