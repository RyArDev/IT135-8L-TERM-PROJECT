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
    $_SESSION['current_page'] = 'announcement';

    include_once('entities/announcement/announcement-controller.php');
    include_once('entities/announcement/announcement-model.php');
    include_once('utilities/validation/server/announcement-input-validation.php');

    include_once('entities/file/file-controller.php');
    include_once('entities/file/file-model.php');
    include_once('utilities/validation/server/file-input-validation.php');

    $announcements = getAllAnnouncements();

    function addAnnouncement($user, &$logDescription, &$logUserId){

        $message = "";
        $type = "";
        $announcementErrors = array();
        
        $announcementCreate = new AnnouncementCreate();
        $announcementCreate->title = isset($_POST['title']) ? $_POST['title'] : null;
        $announcementCreate->body = isset($_POST['body']) ? $_POST['body'] : null;
        $announcementCreate->userId = $user['user_id'];
        $announcementCreate->announcementTypeId = isset($_POST['type']) ? $_POST['type'] : 1;

        $announcementCreate = sanitizeAnnouncementClass($announcementCreate);
        $announcementErrors = validateAddAnnouncement($announcementCreate);

        $announcementCreateLog = new LogAnnouncementCreate();
        $announcementCreateLog->title = $announcementCreate->title;
        $announcementCreateLog->time = new DateTime();
        $announcementCreateLog->status = 'announcement creation pending';
        $announcementCreateLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $announcementCreateLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $announcementCreateLog->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $logUserId = $announcementCreate->userId;

        if (!empty($announcementErrors)){

            $message .= "Announcement Creation Error:<br>";

                foreach ($announcementErrors as $error) {
 
                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                $announcementCreateLog->status = 'announcement creation failed';
                $logDescription = createLogAnnouncementCreate($announcementCreateLog);
                return;

        }

        $announcementCreateSuccess = createAnnouncement($announcementCreate);

        if (!$announcementCreateSuccess) {

            $message = 'Announcement Created Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $announcementCreateLog->status = 'announcement creation failed';
            $logDescription = createLogAnnouncementCreate($announcementCreateLog);
            return;

        }

        $latestAnnouncement = getLatestAnnouncement();

        $announcementEdit = new AnnouncementEdit();
        $announcementEdit->announcementId = $latestAnnouncement['announcement_id'];
        $announcementEdit->title = $announcementCreate->title;
        $announcementEdit->announcementTypeId = $announcementCreate->announcementTypeId;

        $previousImagePaths = array();
        $announcementEdit->body = sanitizeAnnouncementInput(moveCkFinderImages($announcementEdit->announcementId, $announcementCreate->body, "Announcement", $previousImagePaths));
        cleanUpCkFinderImageDirectory($announcementEdit->announcementId, "Announcement", $previousImagePaths);

        updateAnnouncementById($announcementEdit);

        $message = 'Announcement Created Successfully!';
        $type = 'success';
        $announcementCreateLog->status = 'announcement creation success';
        $logDescription = createLogAnnouncementCreate($announcementCreateLog);

        showAlert($message, $type);

    }

    function editAnnouncement($user, &$logDescription, &$logUserId){

        $message = "";
        $type = "";
        $announcementErrors = array();

        $announcementEdit = new AnnouncementEdit();
        $announcementEdit->announcementId = isset($_POST['editId']) ? $_POST['editId'] : null;
        $announcementEdit->title = isset($_POST['editTitle']) ? $_POST['editTitle'] : null;
        $announcementEdit->body = isset($_POST['editBody']) ? $_POST['editBody'] : null;
        $announcementEdit->announcementTypeId = isset($_POST['editType']) ? $_POST['editType'] : 1;

        $announcementEdit = sanitizeAnnouncementClass($announcementEdit);
        $announcementErrors = validateEditAnnouncement($announcementEdit);

        $announcementEditLog = new LogAnnouncementEdit();
        $announcementEditLog->announcementId = $announcementEdit->announcementId;
        $announcementEditLog->userId = $user['user_id'];
        $logUserId = $user['user_id'];
        $announcementEditLog->time = new DateTime();
        $announcementEditLog->status = 'announcement edit pending';
        $announcementEditLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $announcementEditLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $announcementEditLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (!empty($announcementErrors)){

            $message .= "Announcement Edit Error:<br>";

                foreach ($announcementErrors as $error) {
 
                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                $announcementEditLog->status = 'announcement edit failed';
                $logDescription = createLogAnnouncementEdit($announcementEditLog);
                return;

        }

        $announcementEditSuccess = updateAnnouncementById($announcementEdit);

        if (!$announcementEditSuccess) {

            $message = 'Announcement Edited Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $announcementEditLog->status = 'announcement edit failed';
            $logDescription = createLogAnnouncementEdit($announcementEditLog);
            return;

        }

        $previousImagePaths = array();
        $announcementEdit->body = sanitizeAnnouncementInput(moveCkFinderImages($announcementEdit->announcementId, $announcementEdit->body, "Announcement", $previousImagePaths));
        cleanUpCkFinderImageDirectory($announcementEdit->announcementId, "Announcement", $previousImagePaths);

        updateAnnouncementById($announcementEdit);

        $message = 'Announcement Edited Successfully!';
        $type = 'success';
        $announcementEditLog->status = 'announcement edit success';
        $logDescription = createLogAnnouncementEdit($announcementEditLog);

        showAlert($message, $type);

    }

    function deleteAnnouncement($user, &$logDescription, &$logUserId){

        $message = "";
        $type = "";

        $previousImagePaths = array();
        moveCkFinderImages($_POST['announcementId'], "", "Announcement", $previousImagePaths);
        cleanUpCkFinderImageDirectory($_POST['announcementId'], "Announcement", $previousImagePaths);

        $announcementDeleteSuccess = deleteAnnouncementById($_POST['announcementId']);

        $announcementDeleteLog = new LogAnnouncementDelete();
        $announcementDeleteLog->announcementId = $_POST['announcementId'];
        $announcementDeleteLog->userId = $user['user_id'];
        $logUserId = $user['user_id'];
        $announcementDeleteLog->time = new DateTime();
        $announcementDeleteLog->status = 'announcement deletion pending';
        $announcementDeleteLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $announcementDeleteLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $announcementDeleteLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (!$announcementDeleteSuccess) {

            $message = 'Announcement Deleted Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $announcementDeleteLog->status = 'announcement deletion failed';
            $logDescription = createLogAnnouncementDelete($announcementDeleteLog);
            return;

        }

        $message = 'Announcement Deleted Successfully!';
        $type = 'success';
        $announcementDeleteLog->status = 'announcement deletion success';
        $logDescription = createLogAnnouncementDelete($announcementDeleteLog);

        showAlert($message, $type);

    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $logCreate = new LogCreate();
        $logCreate->tableName = 'announcements';
        $logCreate->dateCreated = new DateTime();
        $logCreate->userId = null;
        
        if (isset($_POST["addAnnouncementForm"])) {

            addAnnouncement($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["editAnnouncementForm"])) {

            editAnnouncement($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["deleteAnnouncementForm"])) {

            deleteAnnouncement($user, $logCreate->description, $logCreate->userId);

        }

        createLog($logCreate);
        $announcements = getAllAnnouncements();
        
    }

?>
<style>
    /* CSS for the Announcement Page, Add and Edit Announcement Forms */

/* Container for the Announcement Page */
#announcement-page {
    padding: 20px;
  }
  
  /* Announcement header */
  #announcement-page h1 {
    font-size: 2em;
    margin-bottom: 20px;
  }
  
  /* Add Announcement button */
  #addAnnouncementButton {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 10px;
  }
  
  /* Add and Edit Announcement Form container */
  .hidden-form {
    display: none;
    width: 80%;
    max-width: 500px;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
  }
  
  /* Form header */
  .hidden-form h2 {
    font-size: 1.5em;
    margin-bottom: 15px;
  }
  
  /* Form labels */
  .hidden-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
  }
  
  /* Form input, select, and textarea */
  .hidden-form input[type="text"],
  .hidden-form input[type="hidden"],
  .hidden-form select,
  .hidden-form textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
  }
  
  /* Form buttons */
  .hidden-form input[type="submit"],
  .hidden-form button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 10px;
  }

  /* CSS for the Announcement List */
#announcementList {
    margin-top: 20px;
  }
  
  /* Style the table */
  #announcementList table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #ccc;
  }
  
  /* Style table header cells */
  #announcementList th {
    background-color: #f0f0f0;
    text-align: left;
    padding: 10px;
  }
  
  /* Style table data cells */
  #announcementList td {
    border: 1px solid #ccc;
    padding: 10px;
  }
  
  /* Style the "Edit Announcement" button */
  .editAnnouncementButton {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 5px;
  }
  
  /* Style the "Delete Announcement" button */
  #announcementList form {
    display: inline-block;
  }
  
  #announcementList button[type="submit"] {
    background-color: #ff0000;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
  }
  
</style>
<div>
    <h1>Announcement Page</h1>
    <?php if(isset($user['role_id'])) echo $user['role_id'] > 2 ? '<button id="addAnnouncementButton" onclick="toggleForm(\'addAnnouncementForm\')">Add Announcement</button>' : null; ?>
</div>

<div id="addAnnouncementForm" class="hidden-form">
    <h2>Add New Announcement</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" placeholder="Title" value="<?php echo isset($_POST['title']) ? $_POST['title'] : null; ?>" required><br>
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
        <textarea name="body" id="body"><?php echo isset($_POST['body']) ? $_POST['body'] : null; ?></textarea><br>

        <input type="submit" name="addAnnouncementForm" value="Submit">
        <button type="button" onclick="toggleForm('addAnnouncementForm')">Cancel</button>
    </form>
</div>

<div id="editAnnouncementForm" class="hidden-form">
    <h2>Edit Announcement</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="editId">ID:</label>
        <input type="text" name="editId" id="editId" placeholder="ID" readonly><br>
        <label for="editTitle">Title:</label>
        <input type="text" name="editTitle" id="editTitle" placeholder="Title" required><br>
        <label for="editType">Type:</label>
        <select name="editType" id="editType" required>
            <option value="0" disabled>Please pick an Announcement Type</option>
            <option value="1">Barangay</option>
            <option value="2">City</option>
            <option value="3">Region</option>
            <option value="4">Nation</option>
            <option value="5">Emergency</option>
            <option value="6">Holiday</option>
        </select><br><br>
        <textarea name="editBody" id="editBody"></textarea><br>

        <input type="submit" name="editAnnouncementForm" value="Submit">
        <button type="button" onclick="toggleForm('editAnnouncementForm')">Cancel</button>
    </form>
</div>

<div>
    <!-- Announcement List -->
    <div id="announcementList">
        <?php  
            if(!isset($announcements)){

            echo "There are currently no announcements!";

            }else{ 
        ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Date Created</th>
                    <th>Date Modified</th>
                    <th>User ID</th>
                    <th>Type ID</th>
                    <?php if (isset($user['role_id']) && $user['role_id'] > 2) { ?>
                        <th>Edit</th>
                        <th>Delete</th>
                    <?php } ?>
                </tr>

                <?php foreach ($announcements as $announcement) { ?>
                    <tr>
                        <td><?php echo $announcement['announcement_id']; ?></td>
                        <td><?php echo $announcement['title']; ?></td>
                        <td><?php echo htmlspecialchars_decode($announcement['body']); ?></td>
                        <td><?php echo $announcement['date_created']; ?></td>
                        <td><?php echo $announcement['date_modified']; ?></td>
                        <td><?php echo $announcement['user_id']; ?></td>
                        <td><?php echo $announcement['announcement_type_id']; ?></td>

                        <?php if (isset($user['role_id']) && $user['role_id'] > 2) { ?>
                            <td>
                                <button class="editAnnouncementButton" onclick="toggleForm('editAnnouncementForm')"
                                    data-id="<?php echo $announcement['announcement_id']; ?>"
                                    data-title="<?php echo $announcement['title']; ?>"
                                    data-body="<?php echo $announcement['body']; ?>"
                                    data-type="<?php echo $announcement['announcement_type_id']; ?>"
                                >
                                    Edit Announcement
                                </button>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="announcementId" value="<?php echo $announcement['announcement_id']; ?>">
                                    <button type="submit" name="deleteAnnouncementForm">Delete Announcement</button>
                                </form>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("alert");
    import_ckEditor([
        ["body", 5000], 
        ["editBody", 5000]
    ]);
    import_js("announcement");
?>
