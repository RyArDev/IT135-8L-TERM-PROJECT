<?php 

    $requestUrl = $_SERVER['REQUEST_URI'];
    $requestUrl = strtok($requestUrl, '?');
    $requestUrl = trim($requestUrl, '/');

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

    if (array_key_exists($requestUrl, $routes)) {
        
        $page = $routes[$requestUrl];

    } else {

        require('components/error-page/error-page.php');

    }

    require('global.php');
?>