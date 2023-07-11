<?php 

    //Gets the uri of the current URL and removes any unnecessary characters
    $requestUrl = $_SERVER['REQUEST_URI'];
    $requestUrl = strtok($requestUrl, '?');
    $requestUrl = trim($requestUrl, '/');

    //Lists of all URI routes with the corresponding PHP file
    $routes = [
        '' => 'components/homepage/homepage.php',
        'admin' => 'components/admin-dashboard/admin-dashboard.php',
        'announcement' => 'components/announcement/announcement.php',
        'barangay' => 'components/barangay-corner/barangay-corner.php',
        'contact-us' => 'components/contact-us/contact-us.php',
        'forum' => 'components/forum/forum.php',
        'history' => 'components/history/history.php',
        'login' => 'components/login/login.php',
        'register' => 'components/register/register.php',
        'services' => 'components/services/services.php',
        'profile' => 'components/user-page/user-page.php',
    ];

    //Checks to see if the current URI exists from the routes lists
    if (array_key_exists($requestUrl, $routes)) {
        
        $page = $routes[$requestUrl]; //Content Page

    } else {

        require('components/error-page/error-page.php'); //Error Page

    }

    require('global.php'); //Base HTML page
?>