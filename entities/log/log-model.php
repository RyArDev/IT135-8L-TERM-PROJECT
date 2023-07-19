<?php

    class LogUserLogin{

        public $userId;
        public $time;
        public $status;
        public $ipAddress;
        public $userAgent;

        /*
            $logUserId = $_SESSION['user_id'];
            $logLoginTime = date('Y-m-d H:i:s');
            $logLoginStatus = 'success';
            $logIPAddress = $_SERVER['REMOTE_ADDR'];
            $logUserAgent = $_SERVER['HTTP_USER_AGENT'];
        */

    }

    class LogUserRegister{

        public $username;
        public $email;
        public $time;
        public $status;
        public $ipAddress;
        public $userAgent;

        /*
            $logUsername = $_POST['username'];
            $logEmail = $_POST['email'];
            $logRegistrationTime = date('Y-m-d H:i:s');
            $logRegistrationStatus = 'success';
            $logIPAddress = $_SERVER['REMOTE_ADDR'];
            $logUserAgent = $_SERVER['HTTP_USER_AGENT'];
        */

    }

?>