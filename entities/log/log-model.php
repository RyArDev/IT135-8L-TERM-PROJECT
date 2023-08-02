<?php

    class Log{

        public $logId;
        public $tableName;
        public $description;
        public $dateCreated;
        public $userId;

    }

    class LogCreate{
        
        public $tableName;
        public $description;
        public $dateCreated;
        public $userId;

    }

    class LogEdit{

        public $logId;
        public $tableName;
        public $description;

    }

    class LogUserLogin{

        public $userId;
        public $username;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

    class LogUserLogout{

        public $userId;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

    class LogUserRefreshToken{

        public $refreshToken;
        public $userId;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

    class LogUserRegister{

        public $username;
        public $email;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

    class LogUserEdit{

        public $userId;
        public $userProfileId;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

    class LogAnnouncementCreate{

        public $title;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

    class LogAnnouncementEdit{

        public $announcementId;
        public $userId;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

    class LogAnnouncementDelete{

        public $announcementId;
        public $userId;
        public $time;
        public $status;
        public $sourceIpAddress;
        public $destinationIpAddress;
        public $userAgent;

    }

?>