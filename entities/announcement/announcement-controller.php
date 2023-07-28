<?php

    require_once(dirname(dirname(__DIR__)).'/utilities/database/db-controller.php');
    require_once(dirname(dirname(__DIR__)).'/entities/announcement/announcement-model.php');
    require_once(dirname(dirname(__DIR__)).'/utilities/error/controller-error-handler.php');

    function getAllAnnouncements(){

        try {

            //Queries the results
            $params = [];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Announcements_GetAll", $params)[0];

            if (empty($results)) {

                return null;

            }

            return $results;

        } catch (Exception $e) {
            
            echo "Getting All Announcements Failed: " . $e->getMessage();
            return;

        }

    }

    function getAnnouncementById($announcementId){

        try {

            //Queries the results
            $params = [$announcementId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Announcements_GetById", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (Exception $e) {
            
            echo "Getting Announcement By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function getLatestAnnouncement(){

        try {

            //Queries the results
            $params = [];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Announcements_GetLatest", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (Exception $e) {
            
            echo "Getting Latest Announcement Failed: " . $e->getMessage();
            return;

        }

    }

    function createAnnouncement(AnnouncementCreate $announcement){

        try {

            // Prepare the query
            $params = [
                $announcement->title,
                $announcement->body,
                $announcement->userId,
                $announcement->announcementTypeId
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Announcements_Create", $params);

            if (!$result) {

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            echo "Adding Announcement To Database Failed: " . $e->getMessage();
            return;

        }

    }

    function updateAnnouncementById(AnnouncementEdit $announcement){

        try{

            // Prepare the query
            $params = [
                $announcement->announcementId,
                $announcement->title,
                $announcement->body,
                $announcement->announcementTypeId
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Announcements_UpdateInfoById", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (Exception $e) {
            
            echo "Updating Announcement Information Failed: " . $e->getMessage();
            return;

        }

    }

    function deleteAnnouncementById($announcementId){

        try{

            // Prepare the query
            $params = [$announcementId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Announcements_DeleteById", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (Exception $e) {
            
            echo "Deleting Announcement Information Failed: " . $e->getMessage();
            return;

        }

    }

?>