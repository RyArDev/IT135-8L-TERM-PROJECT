<?php

    require_once(dirname(dirname(__DIR__)).'/utilities/database/db-controller.php');
    require_once(dirname(dirname(__DIR__)).'/entities/forum/forum-model.php');
    require_once(dirname(dirname(__DIR__)).'/entities/forum/forum-type-model.php');
    require_once(dirname(dirname(__DIR__)).'/utilities/error/controller-error-handler.php');
    require_once(dirname(dirname(__DIR__)).'/plugins/jwt-token.php');

    function getAllForums(){

        try {

            //Queries the results
            $params = [];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Forums_GetAll", $params)[0];

            if (empty($results)) {

                return null;

            }

            return $results;

        } catch (Exception $e) {
            
            echo "Getting All Forums Failed: " . $e->getMessage();
            return;

        }

    }

    function getAllForumsByType($forumType){

        try {

            //Queries the results
            $params = [$forumType];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Forums_GetAllByType", $params)[0];

            if (empty($results)) {

                return null;

            }

            return $results;

        } catch (Exception $e) {
            
            echo "Getting All Forum By Types Failed: " . $e->getMessage();
            return;

        }

    }

    function getAllForumTypes(){

        try {

            //Queries the results
            $params = [];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Forums_GetAllTypes", $params)[0];

            if (empty($results)) {

                return null;

            }

            return $results;

        } catch (Exception $e) {
            
            echo "Getting All Forum Types Failed: " . $e->getMessage();
            return;

        }

    }

    function getLatestForum(){

        try {

            //Queries the results
            $params = [];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_GetLatest", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (Exception $e) {
            
            echo "Getting Latest Forum Failed: " . $e->getMessage();
            return;

        }

    }

    function getForumBySearchCriteria($searchValue){

        try {

            //Queries the results
            $params = [$searchValue];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Forums_GetBySearchCriteria", $params)[0];

            if (empty($results)) {

                return null;

            }

            return $results;

        } catch (Exception $e) {
            
            echo "Getting Searched Forums Failed: " . $e->getMessage();
            return;

        }

    }

    function getForumTypeBySearchCriteria($searchValue){

        try {

            //Queries the results
            $params = [$searchValue];

            //Queries the results
            $results = executeStoredProcedure("WebApp_Forums_GetTypeBySearchCriteria", $params)[0];

            if (empty($results)) {

                return null;

            }

            return $results;

        } catch (Exception $e) {
            
            echo "Getting Searched Forum Types Failed: " . $e->getMessage();
            return;

        }

    }

    function getForumById($forumId){

        try {

            //Queries the results
            $params = [$forumId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_GetById", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (Exception $e) {
            
            echo "Getting Forum By Id Failed: " . $e->getMessage();
            return;

        }

    }

    function getForumByType($forumType){

        try {

            //Queries the results
            $params = [$forumType];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_GetByType", $params)[0];

            if (empty($result)) {

                return null;

            }

            return $result[0];

        } catch (Exception $e) {
            
            echo "Getting Forum Types By Type Failed: " . $e->getMessage();
            return;

        }

    }

    function createForum(ForumCreate $forum){

        try {

            // Prepare the query
            $params = [
                $forum->title,
                $forum->body,
                $forum->userId,
                $forum->forumTypeId
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_Create", $params);

            if (!$result) {

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            echo "Adding Forum To Database Failed: " . $e->getMessage();
            return;

        }

    }

    function createForumType(ForumTypeCreate $forumType){

        try {

            // Prepare the query
            $params = [
                $forumType->type
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_CreateType", $params);

            if (!$result) {

                return false;

            }

            return true;

        } catch (Exception $e) {
            
            echo "Adding Forum Type To Database Failed: " . $e->getMessage();
            return;

        }

    }

    function updateForumById(ForumEdit $forum){

        try{

            // Prepare the query
            $params = [
                $forum->forumId,
                $forum->title,
                $forum->body
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_UpdateInfoById", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (Exception $e) {
            
            echo "Updating Forum Information Failed: " . $e->getMessage();
            return;

        }

    }

    function updateForumTypeById(ForumTypeEdit $forumType){

        try{

            // Prepare the query
            $params = [
                $forumType->forumTypeId,
                $forumType->type
            ];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_UpdateTypeInfoById", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (Exception $e) {
            
            echo "Updating Forum Type Information Failed: " . $e->getMessage();
            return;

        }

    }

    function deleteForumById($forumId){

        try{

            // Prepare the query
            $params = [$forumId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_DeleteById", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (Exception $e) {
            
            echo "Deleting Forum Information Failed: " . $e->getMessage();
            return;

        }

    }

    function deleteForumTypeById($forumTypeId){

        try{

            // Prepare the query
            $params = [$forumTypeId];

            //Queries the results
            $result = executeStoredProcedure("WebApp_Forums_DeleteTypeById", $params);

            if (!$result) {

                return false;

            }

            return true;
            
        } catch (Exception $e) {
            
            echo "Deleting Forum Type Information Failed: " . $e->getMessage();
            return;

        }

    }

    //------------------------------------------------AJAX------------------------------------------------//

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

        session_start();

        switch ($_POST['action']) {
    
            default:{

                header("HTTP/1.1 404 Not Found");
                break;

            }

        }

    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {

        session_start();

        switch ($_GET['action']) {

            case 'getForumById':{

                if(!validateJWTToken('user') && !validateJWTToken('officer') && !validateJWTToken('admin')){

                    http_response_code(401);

                }else{

                    $id = $_GET['id'];
                    $forumById = getForumById($id);
                    header('Content-Type: application/json');
                    echo json_encode(['forumById' => $forumById]);

                }

                break;

            }

            case 'getAllForumsByType':{

                if(!validateJWTToken('user') && !validateJWTToken('officer') && !validateJWTToken('admin')){

                    http_response_code(401);

                }else{

                    $type = $_GET['type'];
                    $forumsByType = getAllForumsByType($type);
                    header('Content-Type: application/json');
                    echo json_encode(['forumsByType' => $forumsByType]);

                }

                break;

            }

            case 'getForumsBySearch':{

                if(!validateJWTToken('user') && !validateJWTToken('officer') && !validateJWTToken('admin')){

                    http_response_code(401);

                }else{

                    $searchValue = $_GET['searchValue'];
                    $searchedForums = getForumBySearchCriteria($searchValue);
                    header('Content-Type: application/json');
                    echo json_encode(['searchedForums' => $searchedForums]);

                }

                break;

            }

            case 'getForumTypesBySearch':{

                if(!validateJWTToken('user') && !validateJWTToken('officer') && !validateJWTToken('admin')){

                    http_response_code(401);

                }else{

                    $searchValue = $_GET['searchValue'];
                    $searchedForumTypes = getForumTypeBySearchCriteria($searchValue);
                    header('Content-Type: application/json');
                    echo json_encode(['searchedForumTypes' => $searchedForumTypes]);

                }

                break;

            }
    
            default:{

                header("HTTP/1.1 404 Not Found");
                break;

            }

        }
    }

?>