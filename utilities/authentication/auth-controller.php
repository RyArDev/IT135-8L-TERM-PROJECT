<?php 
    
    require_once('entities/user/user-controller.php');
    require_once('utilities/error/controller-error-handler.php');

    function checkUserLogin() {
    
        try {

            if (!isset($_SESSION['user_id'])) {

                return null;

            }

            $activeStatus = updateUserStatusById($_SESSION['user_id'], 1);

            if(!$activeStatus){

                return null;

            }

            return getUserById($_SESSION['user_id']);

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

?>