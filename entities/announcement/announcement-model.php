<?php

    class Announcement{

        public $announcementId;
        public $title;
        public $body;
        public $dateCreated;
        public $dateModified;
        public $userId;
        public $announcementTypeId;

    }

    class AnnouncementCreate{

        public $title;
        public $body;
        public $userId;
        public $announcementTypeId;

    }

    class AnnouncementEdit{

        public $announcementId;
        public $title;
        public $body;
        public $announcementTypeId;

    }

    class AnnouncementUser{

        public $announcementId;
        public $userId;

    }
    
?>