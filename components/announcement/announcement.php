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
    $_SESSION['current_page'] = "announcement";

    include_once('entities/announcement/announcement-controller.php');
    include_once('entities/announcement/announcement-model.php');
    include_once('utilities/validation/server/announcement-input-validation.php');

    include_once('entities/file/file-controller.php');
    include_once('entities/file/file-model.php');
    include_once('utilities/validation/server/file-input-validation.php');

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
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="0" <?php echo isset($_POST['type']) && $_POST['type'] === '0' ? ' selected' : ''; ?> disabled>Please pick an Announcement Type</option>
            <option value="1" <?php echo isset($_POST['type']) && $_POST['type'] === '1' ? ' selected' : ''; ?>>Barangay</option>
            <option value="2" <?php echo isset($_POST['type']) && $_POST['type'] === '2' ? ' selected' : ''; ?>>City</option>
            <option value="3" <?php echo isset($_POST['type']) && $_POST['type'] === '3' ? ' selected' : ''; ?>>Region</option>
            <option value="4" <?php echo isset($_POST['type']) && $_POST['type'] === '4' ? ' selected' : ''; ?>>Nation</option>
            <option value="5" <?php echo isset($_POST['type']) && $_POST['type'] === '5' ? ' selected' : ''; ?>>Emergency</option>
            <option value="6" <?php echo isset($_POST['type']) && $_POST['type'] === '6' ? ' selected' : ''; ?>>Holiday</option>
        </select><br><br>
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
