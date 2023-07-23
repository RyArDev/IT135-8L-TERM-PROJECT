<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("homepage");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');
    
    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "homepage";
?>

<div>
    Hello This is the homepage!
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("homepage");
    import_js("alert");
?>