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

    if($user['role_id'] < 3){

        header("Location: home");

    }

    include_once('entities/announcement/announcement-controller.php');
    include_once('entities/announcement/announcement-model.php');
    include_once('utilities/validation/server/announcement-input-validation.php');

    include_once('entities/comment/comment-controller.php');
    include_once('entities/comment/comment-model.php');
    include_once('utilities/validation/server/comment-input-validation.php');

    include_once('entities/file/file-controller.php');
    include_once('entities/file/file-model.php');
    include_once('utilities/validation/server/file-input-validation.php');

    include_once('entities/form/form-controller.php');
    include_once('entities/form/form-model.php');
    include_once('utilities/validation/server/form-input-validation.php');

    include_once('entities/forum/forum-controller.php');
    include_once('entities/forum/forum-model.php');
    include_once('utilities/validation/server/forum-input-validation.php');

    include_once('entities/log/log-controller.php');
    include_once('entities/log/log-model.php');
    include_once('utilities/validation/server/log-input-validation.php');

    include_once('entities/ticket/ticket-controller.php');
    include_once('entities/ticket/ticket-model.php');
    include_once('utilities/validation/server/ticket-input-validation.php');

    include_once('entities/user/user-controller.php');
    include_once('entities/user/user-model.php');
    include_once('utilities/validation/server/user-input-validation.php');


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