<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("admin-dashboard");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
?>

<div>
    Hello this is the admin dashboard!
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("admin-dashboard");
    import_js("alert");
?>