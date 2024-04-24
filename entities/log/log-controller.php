<?php

    require_once(dirname(dirname(__DIR__)).'/utilities/database/db-controller.php');
    require_once(dirname(dirname(__DIR__)).'/entities/log/log-model.php');
    require_once(dirname(dirname(__DIR__)).'/utilities/error/controller-error-handler.php');
    require_once(dirname(dirname(__DIR__)).'/plugins/jwt-token.php');

    function createLog(LogCreate $logCreate){

        try {

            //Queries the results
            $params = [
                $logCreate->tableName,
                $logCreate->description,
                $logCreate->dateCreated,
                $logCreate->userId
            ];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Logs_Add", $params);

            if (!$results) {

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            echo "Creating Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function getAllLogs(){

        try {

            //Queries the results
            $params = [];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Logs_GetAll", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result;

        } catch (Exception $e) {
            
            echo "Getting All Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function getLogsBySearchCriteria($searchValue){

        try {

            //Queries the results
            $params = [$searchValue];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Logs_GetBySearchCriteria", $params)[0];

            if (empty($results)) {

                return null;

            }

            return $results;

        } catch (Exception $e) {
            
            echo "Getting Searched Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function getLogsById($logId){

        try {

            //Queries the results
            $params = [$logId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Logs_GetById", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (Exception $e) {
            
            echo "Getting Log By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function getLogsByTableName($tableName){

        try {

            //Queries the results
            $params = [$tableName];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Logs_GetByTableName", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result;

        } catch (Exception $e) {
            
            echo "Getting Logs By Table Name Failed: " . $e->getMessage();
            return;

        }

    }

    function updateLogById(LogEdit $logEdit){

        try {

            //Queries the results
            $params = [
                $logEdit->logId, 
                $logEdit->tableName, 
                $logEdit->description
            ];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Logs_UpdateById", $params);

            if (!$results) {

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            echo "Updating Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function deleteLogById($logId){

        try {

            //Queries the results
            $params = [$logId];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Logs_DeleteById", $params);

            if (!$results) {

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            echo "Deleting Logs Failed: " . $e->getMessage();
            return;

        }

    }

    //------------------------------------------------AJAX------------------------------------------------//

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

        session_start();

        switch ($_POST['action']) {

            case 'temp':{

                break;

            }
    
            default:{

                header("HTTP/1.1 404 Not Found");
                http_response_code(404);
                break;

            }

        }

    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {

        session_start();

        switch ($_GET['action']) {

            case 'getLogsBySearch':{

                if(!validateJWTToken('admin')){

                    http_response_code(401);

                }else{

                    $searchValue = $_GET['searchValue'];
                    $logs = getLogsBySearchCriteria($searchValue);
                    header('Content-Type: application/json');
                    echo json_encode(['logs' => $logs]);

                }

                break;

            }
    
            default:{

                header("HTTP/1.1 404 Not Found");
                http_response_code(404);
                break;

            }

        }
        
    }

    //------------------------------------------------Specific Log Creation------------------------------------------------//

    function createLogUserLogin(LogUserLogin $loginLog){

        try {

            $description = array(
                'user_id' => $loginLog->userId,
                'username' => $loginLog->username,
                'time' => $loginLog->time,
                'status' => $loginLog->status,
                'source_ip_address' => $loginLog->sourceIpAddress,
                'destination_ip_address' => $loginLog->destinationIpAddress,
                'user_agent' => $loginLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Login Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogUserLogout(LogUserLogout $logoutLog){

        try {

            $description = array(
                'user_id' => $logoutLog->userId,
                'time' => $logoutLog->time,
                'status' => $logoutLog->status,
                'source_ip_address' => $logoutLog->sourceIpAddress,
                'destination_ip_address' => $logoutLog->destinationIpAddress,
                'user_agent' => $logoutLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Login Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogUserRegister(LogUserRegister $registerLog){

        try {

            $description = array(
                'username' => $registerLog->username,
                'email' => $registerLog->email,
                'time' => $registerLog->time,
                'status' => $registerLog->status,
                'source_ip_address' => $registerLog->sourceIpAddress,
                'destination_ip_address' => $registerLog->destinationIpAddress,
                'user_agent' => $registerLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Register Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogUserEdit(LogUserEdit $userEditLog){

        try {

            $description = array(
                'user_id' => $userEditLog->userId,
                'profile_id' => $userEditLog->userProfileId,
                'time' => $userEditLog->time,
                'status' => $userEditLog->status,
                'source_ip_address' => $userEditLog->sourceIpAddress,
                'destination_ip_address' => $userEditLog->destinationIpAddress,
                'user_agent' => $userEditLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating User Edit Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogUserRefreshToken(LogUserRefreshToken $userRefreshTokenLog){

        try {

            $description = array(
                'refresh_token' => $userRefreshTokenLog->refreshToken,
                'user_id' => $userRefreshTokenLog->userId,
                'time' => $userRefreshTokenLog->time,
                'status' => $userRefreshTokenLog->status,
                'source_ip_address' => $userRefreshTokenLog->sourceIpAddress,
                'destination_ip_address' => $userRefreshTokenLog->destinationIpAddress,
                'user_agent' => $userRefreshTokenLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating User Edit Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogAnnouncementCreate(LogAnnouncementCreate $announcementCreateLog){

        try {

            $description = array(
                'title' => $announcementCreateLog->title,
                'time' => $announcementCreateLog->time,
                'status' => $announcementCreateLog->status,
                'source_ip_address' => $announcementCreateLog->sourceIpAddress,
                'destination_ip_address' => $announcementCreateLog->destinationIpAddress,
                'user_agent' => $announcementCreateLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Announcement Creation Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogAnnouncementEdit(LogAnnouncementEdit $announcementEditLog){

        try {

            $description = array(
                'announcement_id' => $announcementEditLog->announcementId,
                'user_id' => $announcementEditLog->userId,
                'time' => $announcementEditLog->time,
                'status' => $announcementEditLog->status,
                'source_ip_address' => $announcementEditLog->sourceIpAddress,
                'destination_ip_address' => $announcementEditLog->destinationIpAddress,
                'user_agent' => $announcementEditLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Announcement Edit Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogAnnouncementDelete(LogAnnouncementDelete $announcementDeleteLog){

        try {

            $description = array(
                'announcement_id' => $announcementDeleteLog->announcementId,
                'user_id' => $announcementDeleteLog->userId,
                'time' => $announcementDeleteLog->time,
                'status' => $announcementDeleteLog->status,
                'source_ip_address' => $announcementDeleteLog->sourceIpAddress,
                'destination_ip_address' => $announcementDeleteLog->destinationIpAddress,
                'user_agent' => $announcementDeleteLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Announcement Deletion Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogForumCreate(LogForumPostCreate $forumCreateLog){

        try {

            $description = array(
                'title' => $forumCreateLog->title,
                'time' => $forumCreateLog->time,
                'status' => $forumCreateLog->status,
                'source_ip_address' => $forumCreateLog->sourceIpAddress,
                'destination_ip_address' => $forumCreateLog->destinationIpAddress,
                'user_agent' => $forumCreateLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Forum Creation Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogForumEdit(LogForumPostEdit $forumEditLog){

        try {

            $description = array(
                'forum_id' => $forumEditLog->forumId,
                'user_id' => $forumEditLog->userId,
                'time' => $forumEditLog->time,
                'status' => $forumEditLog->status,
                'source_ip_address' => $forumEditLog->sourceIpAddress,
                'destination_ip_address' => $forumEditLog->destinationIpAddress,
                'user_agent' => $forumEditLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Forum Edit Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogForumDelete(LogForumPostDelete $forumDeleteLog){

        try {

            $description = array(
                'forum_id' => $forumDeleteLog->forumId,
                'user_id' => $forumDeleteLog->userId,
                'time' => $forumDeleteLog->time,
                'status' => $forumDeleteLog->status,
                'source_ip_address' => $forumDeleteLog->sourceIpAddress,
                'destination_ip_address' => $forumDeleteLog->destinationIpAddress,
                'user_agent' => $forumDeleteLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Forum Deletion Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogForumTypeCreate(LogForumTypeCreate $forumTypeCreateLog){

        try {

            $description = array(
                'type' => $forumTypeCreateLog->type,
                'time' => $forumTypeCreateLog->time,
                'status' => $forumTypeCreateLog->status,
                'source_ip_address' => $forumTypeCreateLog->sourceIpAddress,
                'destination_ip_address' => $forumTypeCreateLog->destinationIpAddress,
                'user_agent' => $forumTypeCreateLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Forum Type Creation Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogForumTypeEdit(LogForumTypeEdit $forumTypeEditLog){

        try {

            $description = array(
                'forum_type_id' => $forumTypeEditLog->forumTypeId,
                'user_id' => $forumTypeEditLog->userId,
                'time' => $forumTypeEditLog->time,
                'status' => $forumTypeEditLog->status,
                'source_ip_address' => $forumTypeEditLog->sourceIpAddress,
                'destination_ip_address' => $forumTypeEditLog->destinationIpAddress,
                'user_agent' => $forumTypeEditLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Forum Type Edit Logs Failed: " . $e->getMessage();
            return;

        }

    }

    function createLogForumTypeDelete(LogForumTypeDelete $forumTypeDeleteLog){

        try {

            $description = array(
                'forum_type_id' => $forumTypeDeleteLog->forumTypeId,
                'user_id' => $forumTypeDeleteLog->userId,
                'time' => $forumTypeDeleteLog->time,
                'status' => $forumTypeDeleteLog->status,
                'source_ip_address' => $forumTypeDeleteLog->sourceIpAddress,
                'destination_ip_address' => $forumTypeDeleteLog->destinationIpAddress,
                'user_agent' => $forumTypeDeleteLog->userAgent
            );

            if (empty($description)) {

                return null;

            }

            return json_encode($description);

        } catch (Exception $e) {
            
            echo "Creating Forum Type Deletion Logs Failed: " . $e->getMessage();
            return;

        }

    }

?>