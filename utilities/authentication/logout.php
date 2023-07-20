<?php

    require_once(dirname(dirname(__DIR__)).'/entities/user/user-controller.php');
    session_start();

    function logout(){

        try {

            $activeStatus = updateUserStatusById($_SESSION['user_id'], 0);
    
            if(!$activeStatus){
    
                return null;
    
            }
    
            //Checks whether the user is logged in
            if(isset($_SESSION['user_id'])){
    
                unset($_SESSION['user_id']);
    
            }
    
            session_destroy();
            header("Location: /");
    
        } catch (Exception $e) {
            
            echo "User Logout Failed: " . $e->getMessage();
            return;
    
        }

    }

    logout();

?>