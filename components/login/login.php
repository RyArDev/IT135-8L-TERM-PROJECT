<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("login");
    import_css("alert");

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "login";
    redirectToProfileIfLoggedIn();
    
    include_once('entities/user/user-model.php');
    include_once('entities/user/user-controller.php');
    include_once('utilities/validation/server/user-input-validation.php');

    //Import Alert
    include_once('components/alert/alert.php');

    function userLogin(&$logDescription, &$logUserId){

        $message = "";
        $type = "";

        $userLogin = new UserLogin();
        $userLogin->username = isset($_POST['username']) ? $_POST['username'] : null;
        $userLogin->password = isset($_POST['password']) ? $_POST['password'] : null;
        $userLogin->refreshToken = generateUserRefreshToken();

        $userLogin = sanitizeUserClass($userLogin);
        $userLoginErrors = validateUserLogin($userLogin);

        $loginLog = new LogUserLogin();
        $loginLog->userId = null;
        $loginLog->username = $userLogin->username;
        $loginLog->time = new DateTime();
        $loginLog->status = 'login pending';
        $loginLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $loginLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $loginLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (!empty($userLoginErrors)) {

            $message .= "<b>User Login Error</b><br>";

            foreach ($userLoginErrors as $error) {

                $message .= "- $error<br>";

            }

            $type = 'error';
            showAlert($message, $type);
            $loginLog->status = 'login failed';
            $logDescription = createLogUserLogin($loginLog);
            return;
            
        }

        $userId = getUserLogin($userLogin)['user_id'];

        if(empty($userId)){

            $message = 'User Logged in Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $loginLog->status = 'login failed';
            $logDescription = createLogUserLogin($loginLog);
            return;

        }

        $message = 'User Logged in Successfully!';
        $type = 'success';

        setRefreshTokenCookies($userLogin->refreshToken);

        $_SESSION['login_success'] = true;
        $_SESSION['alert_message'] = $message;
        $_SESSION['alert_type'] = $type;

        $loginLog->userId = $userId;
        $logUserId = $userId;
        $loginLog->status = 'login success';
        $logDescription = createLogUserLogin($loginLog);

        $_SESSION['user_id'] = $userId;
        header("Location: profile");

    }

    // Check for the register status and show the alert if successful
    if (isset($_SESSION['register_success']) && 
        $_SESSION['register_success'] &&
        isset($_SESSION['alert_message']) &&
        isset($_SESSION['alert_type'])
    ) {

        showAlert($_SESSION['alert_message'],  $_SESSION['alert_type']);
        unset($_SESSION['login_success']);
        unset($_SESSION['alert_message']);
        unset($_SESSION['alert_type']);

    }

    //Form Submission for Login
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        
        $logCreate = new LogCreate();
        $logCreate->tableName = 'users';
        $logCreate->dateCreated = new DateTime();
        $logCreate->userId = null;

        userLogin($logCreate->description, $logCreate->userId);
        createLog($logCreate);
    }
?>

<div class="log-content">
<br>
<div class="login">
    <h2 class="logtext">Login</h2>
    <form method="POST" class="log-info">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" class="log-button" value="Login">
    </form>
</div>
<br>
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("login");
    import_js("alert");
?>