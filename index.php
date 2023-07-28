<?php 

    session_start();
    
    //Gets the uri of the current URL and removes any unnecessary characters
    $requestUrl = $_SERVER['REQUEST_URI'];
    $requestUrl = strtok($requestUrl, '?');
    $requestUrl = trim($requestUrl, '/');

    //Imports the specific page according to the URL set from the config file
    require('utilities/validation/server/route-validation.php');
    $page = import_route($requestUrl);

    $errorPage = 'components/error-page/error-page.php'; //Error Page

    require('utilities/settings/cookies.php'); //Setting Cookies
    require('global.php'); //Base HTML page
    require_once('utilities/authentication/auth-controller.php'); //Authenticate User Session
    require_once('vendor/autoload.php'); //This loads project dependencies from Composer
?>