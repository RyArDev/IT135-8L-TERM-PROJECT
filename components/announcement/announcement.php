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
    <h1>Announcement Page</h1>

    <?php if(isset($user['role_id'])) echo $user['role_id'] > 2 ? '<button id="addAnnouncementBtn" onclick="showAnnouncementForm()">Add Announcement</button>' : null; ?>

    <!-- Announcement List -->
    <ul id="announcementList">
        Announcement List
    </ul>
</div>

<div id="addAnnouncementForm" class="hidden-form">
    <h2>Add New Announcement</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" required><br>
        <textarea name="body" id="body" required></textarea><br>

        <input type="submit" name="addAnnouncementForm" value="Submit">
        <button type="button" onclick="hideAnnouncementForm()">Cancel</button>
    </form>
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("announcement");
    import_js("alert");
    import_ckEditor([["body", 5000]]);
?>
