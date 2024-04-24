<?php

    require_once(dirname(dirname(__DIR__)).'/entities/user/user-controller.php');
    require_once(dirname(dirname(__DIR__)).'/entities/log/log-controller.php');
    require_once(dirname(dirname(__DIR__)).'/utilities/settings/cookies.php');
    session_start();

    function logout(){

        $logCreate = new LogCreate();
        $logCreate->tableName = 'users';
        $logCreate->dateCreated = new DateTime();
        $logCreate->userId = $_SESSION['user_id'];

        try {

            //Checks whether the user is logged in
            if(isset($_SESSION['user_id'])){

                $logoutLog = new LogUserLogout();
                $logoutLog->userId = $_SESSION['user_id'];
                $logoutLog->time = new DateTime();
                $logoutLog->status = 'logout pending';
                $logoutLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
                $logoutLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
                $logoutLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

                $activeStatus = updateUserStatusById($_SESSION['user_id'], 0);
        
                if(!$activeStatus){
        
                    $logoutLog->status = 'logout failed by active status update';
                    $logCreate->description = createLogUserLogout($logoutLog);
                    return null;
        
                }
        
                $logoutLog->userId = $_SESSION['user_id'];
                $logoutLog->status = 'logout success';
                $logCreate->description = createLogUserLogout($logoutLog);

                unset($_SESSION['user_id']);
                removeCookie('refresh_token');
                
            }

            createLog($logCreate);

            session_destroy();
            header("Location: /login");
    
        } catch (Exception $e) {
            
            echo "User Logout Failed: " . $e->getMessage();
            return;
    
        }

    }

    logout();

?>