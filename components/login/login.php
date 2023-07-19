<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("login");
    import_css("alert");

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();

    if(isset($user['user_id'])){

        header("Location: profile");
        
    }
    
    include_once('entities/user/user-model.php');
    include_once('entities/user/user-controller.php');

    include_once('utilities/validation/server/user-input-validation.php');

    //Import Alert
    include_once('components/alert/alert.php');

    function userLogin(){

        $message = "";
        $type = "";

        $userLogin = new UserLogin();
        $userLogin->username = isset($_POST['username']) ? $_POST['username'] : null;
        $userLogin->password = isset($_POST['password']) ? $_POST['password'] : null;

        $userLogin = sanitizeClass($userLogin);
        $userLoginErrors = validateUserLogin($userLogin);

        if (!empty($userLoginErrors)) {

            $message .= "<b>User Login Error</b><br>";

            foreach ($userLoginErrors as $error) {

                $message .= "- $error<br>";

            }

            $type = 'error';
            showAlert($message, $type);
            return;
            
        }

        $userId = getUserLogin($userLogin)['user_id'];

        if(empty($userId)){

            $message = 'User Logged in Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            return;

        }

        $message = 'User Logged in Successfully!';
        $type = 'success';

        $_SESSION['login_success'] = true;
        $_SESSION['alert_message'] = $message;
        $_SESSION['alert_type'] = $type;

        $_SESSION['user_id'] = $userId;
        header("Location: profile");

    }

    // Check for the register status and show the alert if successful
    if (isset($_SESSION['register_success']) && $_SESSION['register_success']) {

        showAlert($_SESSION['alert_message'],  $_SESSION['alert_type']);
        unset($_SESSION['login_success']);
        unset($_SESSION['alert_message']);
        unset($_SESSION['alert_type']);

    }

    //Form Submission for Login
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        userLogin();

    }

?>

<div>
    <h2>Login</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("login");
    import_js("alert");
?>