<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("forum");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
?>

<div>
    This is the forum page!
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("forum");
    import_js("alert");
?>