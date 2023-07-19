<?php

    session_start();

    try {

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

?>