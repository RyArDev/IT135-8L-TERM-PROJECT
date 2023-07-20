<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("announcement");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
?>

<div>
    This is an announcement!
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("announcement");
    import_js("alert");
?>
