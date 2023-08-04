<?php

    class Forum{

        public $forumId;
        public $title;
        public $body;
        public $userId;
        public $forumTypeId;

    }

    class ForumCreate{
        
        public $title;
        public $body;
        public $userId;
        public $forumTypeId;

    }

    class ForumEdit{

        public $forumId;
        public $title;
        public $body;
        public $forumTypeId;

    }

    class ForumUser{

        public $forumId;
        public $userId;

    }

?>