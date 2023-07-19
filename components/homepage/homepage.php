<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("homepage");
    
    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
?>

<div>
    Hello This is the homepage!
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("homepage");
?>