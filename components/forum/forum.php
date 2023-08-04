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
    $_SESSION['current_page'] = "forum";

    include_once('entities/forum/forum-controller.php');
    include_once('entities/forum/forum-model.php');
    include_once('entities/forum/forum-type-model.php');
    include_once('utilities/validation/server/forum-input-validation.php');

    include_once('entities/file/file-controller.php');
    include_once('entities/file/file-model.php');
    include_once('utilities/validation/server/file-input-validation.php');

    $forumType = getAllForumTypes();

    function addForumType($user, &$logDescription, &$logUserId){

        $message = "";
        $type = "";
        $forumTypeErrors = array();
        
        $forumTypeCreate = new ForumTypeCreate();
        $forumTypeCreate->type = isset($_POST['forumTopicType']) ? $_POST['forumTopicType'] : null;

        $forumTypeCreate = sanitizeForumClass($forumTypeCreate);
        $forumTypeErrors = validateAddForumType($forumTypeCreate);

        $forumTypeCreateLog = new LogForumTypeCreate();
        $forumTypeCreateLog->type = $forumTypeCreate->type;
        $forumTypeCreateLog->time = new DateTime();
        $forumTypeCreateLog->status = 'forum type creation pending';
        $forumTypeCreateLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $forumTypeCreateLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $forumTypeCreateLog->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $logUserId = $user['user_id'];

        if (!empty($forumTypeErrors)){

            $message .= "Forum Topic Creation Error:<br>";

                foreach ($forumTypeErrors as $error) {
 
                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                $forumTypeCreateLog->status = 'forum type creation failed';
                $logDescription = createLogForumTypeCreate($forumTypeCreateLog);
                return;

        }

        $forumTypeCreateSuccess = createForumType($forumTypeCreate);

        if (!$forumTypeCreateSuccess) {

            $message = 'Forum Topic Created Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $forumTypeCreateLog->status = 'forum type creation failed';
            $logDescription = createLogForumTypeCreate($forumTypeCreateLog);
            return;

        }

        $message = 'Forum Type Created Successfully!';
        $type = 'success';
        $forumTypeCreateLog->status = 'forum type creation success';
        $logDescription = createLogForumTypeCreate($forumTypeCreateLog);

        showAlert($message, $type);

    }

    function addForumPost($user, &$logDescription, &$logUserId){

        $message = "";
        $type = "";
        $forumPostErrors = array();
        
        $forumPostCreate = new ForumCreate();
        $forumPostCreate->title = isset($_POST['addForumPostTitle']) ? $_POST['addForumPostTitle'] : null;
        $forumPostCreate->body = isset($_POST['addForumPostBody']) ? $_POST['addForumPostBody'] : null;
        $forumPostCreate->forumTypeId = isset($_POST['addForumTypeId']) ? $_POST['addForumTypeId'] : null;
        $forumPostCreate->userId = $user['user_id'];

        $forumPostCreate = sanitizeForumClass($forumPostCreate);
        $forumPostErrors = validateAddForumPost($forumPostCreate);

        $forumPostCreateLog = new LogForumPostCreate();
        $forumPostCreateLog->title = $forumPostCreate->title;
        $forumPostCreateLog->time = new DateTime();
        $forumPostCreateLog->status = 'forum type creation pending';
        $forumPostCreateLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $forumPostCreateLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $forumPostCreateLog->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $logUserId = $user['user_id'];

        if (!empty($forumPostErrors)){

            $message .= "Forum Post Creation Error:<br>";

                foreach ($forumPostErrors as $error) {
 
                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                $forumPostCreateLog->status = 'forum post creation failed';
                $logDescription = createLogForumCreate($forumPostCreateLog);
                return;

        }

        $forumPostCreateSuccess = createForum($forumPostCreate);

        if (!$forumPostCreateSuccess) {

            $message = 'Forum Post Created Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $forumPostCreateLog->status = 'forum post creation failed';
            $logDescription = createLogForumCreate($forumPostCreateLog);
            return;

        }

        $latestForumPost = getLatestForum();

        $forumEdit = new ForumEdit();
        $forumEdit->forumId = $latestForumPost['forum_id'];
        $forumEdit->title = $forumPostCreate->title;
        $forumEdit->forumTypeId = $forumPostCreate->forumTypeId;

        $previousImagePaths = array();
        $forumEdit->body = sanitizeForumInput(moveCkFinderImages($forumEdit->forumId, $forumPostCreate->body, "Forum", $previousImagePaths));
        cleanUpCkFinderImageDirectory($forumEdit->forumId, "Forum", $previousImagePaths);

        updateForumById($forumEdit);

        $message = 'Forum Post Created Successfully!';
        $type = 'success';
        $forumPostCreateLog->status = 'forum post creation success';
        $logDescription = createLogForumCreate($forumPostCreateLog);

        showAlert($message, $type);

    }

    function editForumPost($user, &$logDescription, &$logUserId){

        $message = "";
        $type = "";
        $forumPostErrors = array();

        $forumEdit = new ForumEdit();
        $forumEdit->forumId = isset($_POST['forumPostId']) ? $_POST['forumPostId'] : null;
        $forumEdit->title = isset($_POST['forumPostTitle']) ? $_POST['forumPostTitle'] : null;
        $forumEdit->body = isset($_POST['forumPostBody']) ? $_POST['forumPostBody'] : null;
        $forumEdit->forumTypeId = isset($_POST['forumPostTypeId']) ? $_POST['forumPostTypeId'] : 1;

        $forumEdit = sanitizeForumClass($forumEdit);
        $forumPostErrors = validateEditForumPost($forumEdit);

        $forumEditLog = new LogForumPostEdit();
        $forumEditLog->forumId = $forumEdit->forumId;
        $forumEditLog->userId = $user['user_id'];
        $logUserId = $user['user_id'];
        $forumEditLog->time = new DateTime();
        $forumEditLog->status = 'forum edit pending';
        $forumEditLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $forumEditLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $forumEditLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (!empty($forumPostErrors)){

            $message .= "Forum Post Edit Error:<br>";

                foreach ($forumPostErrors as $error) {
 
                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                $forumEditLog->status = 'forum edit failed';
                $logDescription = createLogForumEdit($forumEditLog);
                return;

        }

        $forumEditSuccess = updateForumById($forumEdit);

        if (!$forumEditSuccess) {

            $message = 'Forum Edited Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $forumEditLog->status = 'forum edit failed';
            $logDescription = createLogForumEdit($forumEditLog);
            return;

        }

        $previousImagePaths = array();
        $forumEdit->body = sanitizeForumInput(moveCkFinderImages($forumEdit->forumId, $forumEdit->body, "Forum", $previousImagePaths));
        cleanUpCkFinderImageDirectory($forumEdit->forumId, "Forum", $previousImagePaths);

        updateForumById($forumEdit);

        $message = 'Forum Edited Successfully!';
        $type = 'success';
        $forumEditLog->status = 'forum edit success';
        $logDescription = createLogForumEdit($forumEditLog);

        showAlert($message, $type);

    }

    function deleteForumPost($user, &$logDescription, &$logUserId){

        $message = "";
        $type = "";

        $previousImagePaths = array();
        moveCkFinderImages($_POST['forumId'], "", "Forum", $previousImagePaths);
        cleanUpCkFinderImageDirectory($_POST['forumId'], "Forum", $previousImagePaths);

        $forumDeleteSuccess = deleteForumById($_POST['forumId']);

        $forumPostDeleteLog = new LogForumPostDelete();
        $forumPostDeleteLog->forumId = $_POST['forumId'];
        $forumPostDeleteLog->userId = $user['user_id'];
        $logUserId = $user['user_id'];
        $forumPostDeleteLog->time = new DateTime();
        $forumPostDeleteLog->status = 'announcement deletion pending';
        $forumPostDeleteLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
        $forumPostDeleteLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
        $forumPostDeleteLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

        if (!$forumDeleteSuccess) {

            $message = 'Forum Post Deleted Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            $forumPostDeleteLog->status = 'forum post deletion failed';
            $logDescription = createLogForumDelete($forumPostDeleteLog);
            return;

        }

        $message = 'Forum Post Deleted Successfully!';
        $type = 'success';
        $forumPostDeleteLog->status = 'forum post deletion success';
        $logDescription = createLogForumDelete($forumPostDeleteLog);

        showAlert($message, $type);

    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $logCreate = new LogCreate();
        $logCreate->tableName = 'forums';
        $logCreate->dateCreated = new DateTime();
        $logCreate->userId = null;
        
        if (isset($_POST["addForumTypeForm"])) {

            addForumType($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["addForumPostForm"])) {

            addForumPost($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["editForumPostForm"])) {

            editForumPost($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["deleteForumPostForm"])) {

            deleteForumPost($user, $logCreate->description, $logCreate->userId);

        }

        createLog($logCreate);
        $forumType = getAllForumTypes();
        
    }

?>

<div id="addForumTypeForm" class="hidden-form">
    <h2>Add a Topic</h2>
    <form method="POST">
        <label for="forumTopicType">Topic:</label>
        <input type="text" name="forumTopicType" id="forumTopicType" placeholder="Topic" required><br>
        
        <input type="submit" name="addForumTypeForm" value="Submit">
        <button type="button" onclick="toggleForm('addForumTypeForm')">Cancel</button>
    </form>
</div>

<div id="forum">
    <h1>Forum Page</h1>
    <button id="addForumTypeButton" onclick="toggleForm('addForumTypeForm')">Add Forum Topic</button><br/>
    <?php
        if(!isset($forumType)){

            echo "<p>There are currently no forum topics.</p>";

        }else{

            foreach($forumType as $topic){

                echo "
                    <a href='#forumTopic' onclick=\"showForumTopic('".$topic['type']."',".$_SESSION['user_id'].", ".$topic['forum_type_id'].")\">
                        ". $topic['type'] ."
                    </a><br/>
                ";

            }

        }
    ?>
</div>

<div id="forumTopic" class="hidden-div">
</div>


<div id="addForumPostForm" class="hidden-form">
    <h2>Add a Post</h2>
    <form method="POST">
        <label for="addForumPostTitle">Title:</label>
        <input type="text" name="addForumPostTitle" id="addForumPostTitle" placeholder="Title" required><br>
        <input type="hidden" name="addForumTypeId" id="addForumTypeId" required><br>
        <textarea name="addForumPostBody" id="addForumPostBody"></textarea><br>
        
        <input type="submit" name="addForumPostForm" value="Submit">
        <button type="button" onclick="toggleForm('addForumPostForm')">Cancel</button>
    </form>
</div>

<div id="editForumPostForm" class="hidden-form">
    <h2>Edit Post</h2>
    <form method="POST">
        <label for="forumPostId">ID:</label>
        <input type="text" name="forumPostId" id="forumPostId" placeholder="ID" readonly><br>
        <label for="forumPostTitle">Title:</label>
        <input type="text" name="forumPostTitle" id="forumPostTitle" placeholder="Title" required><br>
        <input type="hidden" name="forumPostTypeId" id="forumPostTypeId" required><br>
        <textarea name="forumPostBody" id="forumPostBody"></textarea><br>
        
        <input type="submit" name="editForumPostForm" value="Submit">
        <button type="button" onclick="toggleForm('editForumPostForm')">Cancel</button>
    </form>
</div>

<div id="forumPost" class="hidden-div">
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("forum");
    import_js("alert");
    import_ckEditor([
        ["forumPostBody", 5000],
        ["addForumPostBody", 5000]
    ]);
?>