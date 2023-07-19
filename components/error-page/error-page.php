<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("error-page");

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
?>

<div>
    This is an Error!
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("error-page");
?>