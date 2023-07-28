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
    $_SESSION['current_page'] = "admin-dashboard";
?>

<div>
    Hello this is the User Moderation!
</div>

<div>
    Hello this is the Announcement Moderation with visuals!
</div>

<div>
    Hello this is the Forum Moderation with visuals!
</div>

<div>
    Hello this is the Comments Moderation with visuals!
</div>

<div>
    Hello this is the Logs Moderation with visuals!
</div>

<div>
    Hello this is the Tickets Moderation with visuals!
</div>

<div>
    Hello this is the Forms Moderation with visuals!
</div>

<div>
    Hello this is the List of Active Users Moderation!
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("admin-dashboard");
    import_js("alert");
?>