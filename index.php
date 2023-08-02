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

    //Adds the logging function of website functions
    require_once('entities/log/log-controller.php');
    require_once('entities/log/log-model.php');
    require_once('utilities/validation/server/log-input-validation.php');
    require_once('utilities/authentication/auth-controller.php'); //Authenticate User Session
    require_once('vendor/autoload.php'); //This loads project dependencies from Composer

    require('utilities/settings/cookies.php'); //Setting Cookies
    require('global.php'); //Base HTML page
?>

<script>
    let jwtToken = "<?php echo isset($_SESSION['jwt_token']) ? $_SESSION['jwt_token'] : null; ?>";
    sessionStorage.setItem('jwt_token', jwtToken);
</script>