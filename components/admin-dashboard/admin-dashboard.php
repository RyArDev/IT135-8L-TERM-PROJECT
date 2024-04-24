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
    $userProfile = checkUserProfile();
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

    function updateUserAndProfile($user, $userProfile, &$logDescription, &$logUserId){

        $message = "";
        $type = "";

        if (isset($_POST["editUserForm"])) {

            $userProfileErrors = array();
            $profileImageErrors = array();

            $userEdit = new UserAdminEdit();
            $userEdit->userId = isset($_POST['userId']) ? $_POST['userId'] : null;
            $userEdit->username = isset($_POST['username']) ? $_POST['username'] : null;
            $userEdit->email = isset($_POST['email']) ? $_POST['email'] : null;
            $userEdit->roleId = isset($_POST['role']) ? $_POST['role'] : null;

            $userProfileEdit = new UserProfileEdit();
            $userProfileEdit->userProfileId = $userProfile['user_profile_id'];
            $userProfileEdit->firstName = isset($_POST['firstName']) ? $_POST['firstName'] : null;
            $userProfileEdit->lastName = isset($_POST['lastName']) ? $_POST['lastName'] : null;
            $userProfileEdit->birthDate = isset($_POST['birthDate']) ? $_POST['birthDate'] : null;
            $userProfileEdit->userId = isset($_POST['userId']) ? $_POST['userId'] : null;

            $fullAddress = "";

            if(isset($_POST['address1'])){

                $fullAddress .= $_POST['address1'].", ";

            }

            if(isset($_POST['address2'])){

                $fullAddress .= $_POST['address2'].", ";

            }else{

                $fullAddress .= ", ";

            }

            if(isset($_POST['city'])){

                $fullAddress .= $_POST['city'].", ";

            }

            if(isset($_POST['stateProvince'])){
                
                $fullAddress .= $_POST['stateProvince'].", ";
            }

            if(isset($_POST['zipCode'])){
            
                $fullAddress .= $_POST['zipCode'];
                
            }

            $userProfileEdit->address = $fullAddress;
            $userProfileEdit->gender = isset($_POST['gender']) ? $_POST['gender'] : null;
            $userProfileEdit->phoneNumber = isset($_POST['phoneNumber']) && $_POST['phoneNumber'] !== "" ? $_POST['phoneNumber'] : null;
            $userProfileEdit->jobTitle = isset($_POST['jobTitle']) && !empty($_POST['jobTitle']) ? $_POST['jobTitle'] : null;
            $userProfileEdit->jobDescription = isset($_POST['jobDescription']) && !empty($_POST['jobDescription']) ? $_POST['jobDescription'] : null;
            $userProfileEdit->profileImageName = null;
            $userProfileEdit->profileImageUrl = null;
            $userProfileEdit->profileBannerName = null;
            $userProfileEdit->profileBannerUrl = null;
            $userProfileEdit->description = isset($_POST['userDescription']) && !empty($_POST['userDescription']) ? $_POST['userDescription'] : null;
            
            $userEdit = sanitizeUserClass($userEdit);
            $userProfileEdit = sanitizeUserClass($userProfileEdit);

            $userErrors = validateAdminUser($userEdit);
            $userProfileErrors = validateUserProfile($userProfileEdit);

            $userEditLog = new LogUserEdit();
            $userEditLog->userId = $userProfileEdit->userId;
            $logUserId = $userProfileEdit->userId;
            $userEditLog->userProfileId = $userProfileEdit->userProfileId;
            $userEditLog->time = new DateTime();
            $userEditLog->status = 'user edit pending';
            $userEditLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
            $userEditLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
            $userEditLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

            if (!empty($userErrors) || !empty($userProfileErrors)) {
                
                $message .= "<b>User Profile Error</b><br>";

                foreach ($userErrors as $error) {

                    $message .= "- $error<br>";

                }

                foreach ($userProfileErrors as $error) {

                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                $userEditLog->status = 'user edit failed';
                $logDescription = createLogUserEdit($userEditLog);
                return;

            }

            if(isset($_FILES["profileImage"]) && $_FILES["profileImage"]["error"] === UPLOAD_ERR_OK) {
                
                $pofileImage = new FileImage();
                $pofileImage->image = $_FILES["profileImage"];
                $pofileImage->name = $_FILES["profileImage"]["name"];
                $pofileImage->type = $_FILES["profileImage"]["type"];
                $pofileImage->size = $_FILES["profileImage"]["size"];
                $pofileImage->tempPath = $_FILES["profileImage"]["tmp_name"];
                $pofileImage->error = $_FILES["profileImage"]["error"];

                $profileImageErrors = validateProfileImage($pofileImage);

                if (!empty($profileImageErrors)) {
                    
                    $message .= "<b>Profile Image Error</b><br>";

                    foreach ($profileImageErrors as $error) {

                        $message .= "- $error<br>";

                    }

                    $type = 'error';
                    showAlert($message, $type);
                    $userEditLog->status = 'user edit profile image failed';
                    $logDescription = createLogUserEdit($userEditLog);
                    return;

                }

                uploadProfileImage($userProfileEdit, $pofileImage);
                
            }

            if(isset($_FILES["profileBanner"]) && $_FILES["profileBanner"]["error"] === UPLOAD_ERR_OK) {
                
                $profileBanner = new FileImage();
                $profileBanner->image = $_FILES["profileBanner"];
                $profileBanner->name = $_FILES["profileBanner"]["name"];
                $profileBanner->type = $_FILES["profileBanner"]["type"];
                $profileBanner->size = $_FILES["profileBanner"]["size"];
                $profileBanner->tempPath = $_FILES["profileBanner"]["tmp_name"];
                $profileBanner->error = $_FILES["profileBanner"]["error"];

                $profileBannerErrors = validateProfileBanner($profileBanner);

                if (!empty($profileBannerErrors)) {
                    
                    $message .= "<b>Profile Banner Error</b><br>";

                    foreach ($profileBannerErrors as $error) {

                        $message .= "- $error<br>";

                    }

                    $type = 'error';
                    showAlert($message, $type);
                    $userEditLog->status = 'user edit profile banner failed';
                    $logDescription = createLogUserEdit($userEditLog);
                    return;

                }

                uploadProfileBanner($userProfileEdit, $profileBanner);
                
            }

            $previousImagePaths = array();
            $userProfileEdit->description = sanitizeUserInput(moveCkFinderImages($userProfileEdit->userId, $userProfileEdit->description, "User", $previousImagePaths));
            $userProfileEdit->jobDescription = sanitizeUserInput(moveCkFinderImages($userProfileEdit->userId, $userProfileEdit->jobDescription, "User", $previousImagePaths));
            cleanUpCkFinderImageDirectory($userProfileEdit->userId, "User", $previousImagePaths);

            $userUpdateSuccess = adminUpdateUser($userEdit);
            $userProfileUpdateSuccess = updateUserProfileByUserId($userProfileEdit);

            if (!$userUpdateSuccess || !$userProfileUpdateSuccess) {

                $message = 'User Profile Updated Unsuccessfully!';
                $type = 'error';
                showAlert($message, $type);
                $userEditLog->status = 'user edit update failed';
                $logDescription = createLogUserEdit($userEditLog);
                return;

            }

            $message = 'User Profile Updated Successfully!';
            $type = 'success';
            $userEditLog->status = 'user edit success';
            $logDescription = createLogUserEdit($userEditLog);

            showAlert($message, $type);

        }else if (isset($_POST["changePasswordForm"])) {

            $userPasswordErrors = array();

            $userEditPassword = new UserEditPassword();
            $userEditPassword->userId = $user['user_id'];
            $userEditPassword->oldPassword = isset($_POST['oldPassword']) ? $_POST['oldPassword'] : null;
            $userEditPassword->newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : null;
            $userEditPassword->confirmNewPassword = isset($_POST['confirmNewPassword']) ? $_POST['confirmNewPassword'] : null;
            
            $userEditPassword = sanitizeUserClass($userEditPassword);
            $userPasswordErrors = validateUserEditPassword($userEditPassword, 'user');

            $userEditLog = new LogUserEdit();
            $userEditLog->userId = $userEditPassword->userId;
            $userEditLog->userProfileId = $userEditPassword->userId;
            $userEditLog->time = new DateTime();
            $userEditLog->status = 'user edit pending';
            $userEditLog->sourceIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'];
            $userEditLog->destinationIpAddress = gethostbyname($_SERVER['HTTP_HOST']);
            $userEditLog->userAgent = $_SERVER['HTTP_USER_AGENT'];

            if (!empty($userPasswordErrors)) {
                
                $message .= "User Password Error:<br>";

                foreach ($userPasswordErrors as $error) {
 
                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                $userEditLog->status = 'user edit password failed';
                $logDescription = createLogUserEdit($userEditLog);
                return;

            }

            $updateUserPasswordSuccess = updateUserPassword($userEditPassword);

            if(!$updateUserPasswordSuccess){

                $message = 'User Password Updated Unsuccessfully!';
                $type = 'error';
                showAlert($message, $type);
                $userEditLog->status = 'user edit password update failed';
                $logDescription = createLogUserEdit($userEditLog);
                return;

            }

            $message = 'User Password Updated Successfully!';
            $type = 'success';
            $userEditLog->status = 'user edit password success';
            $logDescription = createLogUserEdit($userEditLog);
            
            showAlert($message, $type);

        }

    }

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

    function editLog(){

        $message = "";
        $type = "";
        $logErrors = array();

        $logEdit = new LogEdit();
        $logEdit->logId = isset($_POST['logId']) ? $_POST['logId'] : null;
        $logEdit->tableName = isset($_POST['logTableName']) ? $_POST['logTableName'] : null;
        $logEdit->description = isset($_POST['logDescription']) ? $_POST['logDescription'] : null;
        $logEdit->description = str_replace("'", '"', $logEdit->description);

        $logErrors = validateEditLog($logEdit);

        if (!empty($logErrors)){

            $message .= "Log Edit Error:<br>";

                foreach ($logErrors as $error) {
 
                    $message .= "- $error<br>";

                }

                $type = 'error';
                showAlert($message, $type);
                return;

        }

        $logEditSuccess = updateLogById($logEdit);

        if (!$logEditSuccess) {

            $message = 'Log Edited Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            return;

        }

        $message = 'Log Edited Successfully!';
        $type = 'success';

        showAlert($message, $type);

    }

    function deleteLog(){

        $message = "";
        $type = "";

        $logDeleteSuccess = deleteLogById($_POST['logId']);

        if (!$logDeleteSuccess) {

            $message = 'Log Deleted Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            return;

        }

        $message = 'Log Deleted Successfully!';
        $type = 'success';

        showAlert($message, $type);

    }

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
        $logCreate->dateCreated = new DateTime();
        $logCreate->userId = null;
        
        if (isset($_POST["addAnnouncementForm"])) {

            $logCreate->tableName = 'announcements';
            addAnnouncement($user, $logCreate->description, $logCreate->userId);
            createLog($logCreate);

        }

        if (isset($_POST["editAnnouncementForm"])) {

            $logCreate->tableName = 'announcements';
            editAnnouncement($user, $logCreate->description, $logCreate->userId);
            createLog($logCreate);

        }

        if (isset($_POST["deleteAnnouncementForm"])) {

            $logCreate->tableName = 'announcements';
            deleteAnnouncement($user, $logCreate->description, $logCreate->userId);
            createLog($logCreate);

        }

        if (isset($_POST['editUserForm']) || isset($_POST['changePasswordForm'])){

            $logCreate->tableName = 'users';
            updateUserAndProfile($user, $userProfile, $logCreate->description, $logCreate->userId);
            createLog($logCreate);

        }

        if (isset($_POST["editLogForm"])) {

            editLog();

        }

        if (isset($_POST["deleteLogForm"])) {

            deleteLog();

        }

        if (isset($_POST["addForumTypeForm"])) {

            $logCreate->tableName = 'forums';
            addForumType($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["addForumPostForm"])) {

            $logCreate->tableName = 'forums';
            addForumPost($user, $logCreate->description, $logCreate->userId);
            createLog($logCreate);

        }

        if (isset($_POST["editForumPostForm"])) {

            $logCreate->tableName = 'forums';
            editForumPost($user, $logCreate->description, $logCreate->userId);
            createLog($logCreate);

        }

        if (isset($_POST["deleteForumPostForm"])) {

            $logCreate->tableName = 'forums';
            deleteForumPost($user, $logCreate->description, $logCreate->userId);
            createLog($logCreate);

        }
        
    }

?>
<style>
/* Basic styling for the whole page */
body {
    font-family: 'Grandis Bold';
    margin: 0;
    padding: 0;
    background-color: #fff;
}

/* Styling for the header */
h1 {
    text-align: center;
    padding: 20px;
    background-color:#0f52BA ;
    color: #fff;
    margin: 0;
}

/* Styling for the search bar */
.search-bar {
    text-align: center;
    padding: 10px;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
}

/* Styling for the hidden form */
.hidden-form {
    display: none;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    margin-top: 20px;
}

/* Styling for form labels and inputs */
form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

form input[type="text"],
form input[type="email"],
form input[type="date"],
form select,
form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

/* Styling for buttons */
button {
    background-color: #0f52BA;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

/* Styling for image previews */
img#previewProfileImage,
img#previewProfileBanner {
    display: block;
    margin: 10px auto;
}

/* Styling for form submission button */
input[type="submit"] {
    background-color: #0f52BA;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

form input[type="text"],
form input[type="password"],
form input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

/* Styling for the users list */
#usersList {
    background-color: #f1f1f1;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    margin-top: 20px;
}

/* Styling for the "Add Announcement" button */
#addAnnouncementButton {
    background-color: #0f52BA;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-top: 10px;
}

#addForumTypeButton {
    background-color: #0f52BA;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-top: 10px;
}

#showActiveUsersButton {
    background-color: #0f52BA;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-top: 10px;
}

/* Styling for the search bar */
.search-bar {
    text-align: center;
    padding: 10px;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
}

/* Styling for the hidden form */
.hidden-form {
    display: none;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    margin-top: 20px;
}

/* Styling for form labels and inputs */
form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

form input[type="text"],
form select,
form textarea,
form input[type="submit"],
form button[type="button"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

/* Responsive styling */
@media screen and (max-width: 768px) {
    form {
        width: 100%;
    }
    
    img#previewProfileBanner {
        max-width: 100%;
    }
}



  
  
</style>
<div>
    <br>
    <h1>User Moderation</h1>
    <div class="search-bar">
        <input type="text" id="searchUserInput" placeholder="Search User">
    </div>
    <div class="hidden-form" id="editUserForm">
        <button onclick="toggleForm('editUserForm')">Close</button>
        <h2>Edit Profile Form</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="userId">User ID:</label>
            <input type="text" name="userId" id="userId" placeholder="ID" readonly><br>
            <label for="profileId">Profile ID:</label>
            <input type="text" name="profileId" id="profileId" placeholder="Profile ID" readonly><br>
            <label for="profileImage">Add Profile Picture (Max 2MB):</label>
            <img src="#" id="previewProfileImage" alt="Preview Profile Image" style="max-width: 200px; max-height: 200px; display: none;">
            <input type="file" name="profileImage" id="profileImage" accept="image/*"><br><br>

            <label for="profileBanner">Add Profile Banner (Max 5MB):</label>
            <img src="#" id="previewProfileBanner" alt="Preview Profile Banner" style="max-width: 970px; max-height: 250px; display: none;">
            <input type="file" name="profileBanner" id="profileBanner" accept="image/*"><br><br>

            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="1">None</option>
                <option value="2">User</option>
                <option value="3">Officer</option>
                <option value="4">Admin</option>
            </select><br><br>

            <label for="userDescription">User Bio:</label>
            <textarea id="userDescription" name="userDescription" placeholder="User Description"></textarea><br><br>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Username" required><br><br>
            
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" placeholder="First Name" required><br><br>
            
            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" placeholder="Last Name" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required><br><br>
            
            <label for="birthDate">Birth Date:</label>
            <input type="date" id="birthDate" name="birthDate" placeholder="Birth Date" required><br><br>
            
            <label for="address1">Address:</label>
            <input type="text" id="address1" name="address1" placeholder="Address Line 1" required><br>
            <input type="text" id="address2" name="address2" placeholder="Address Line 2"><br><br>
            
            <label for="city">City:</label>
            <input type="text" id="city" name="city" placeholder="City" required><br><br>
            
            <label for="stateProvince">State/Province:</label>
            <input type="text" id="stateProvince" name="stateProvince" placeholder="State / Province" required><br><br>

            <label for="zipCode">Zip Code:</label>
            <input type="text" id="zipCode" name="zipCode" placeholder="Zip Code" required><br><br>

            <label for="gender">Gender:</label>
            <select name="gender" id="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br><br>
            
            <label for="phoneNumber">Phone Number:</label>
            <input type="text" id="phoneNumber" name="phoneNumber" placeholder="Phone Number"><br><br>

            <label for="jobTitle">Job Title:</label>
            <input type="text" id="jobTitle" name="jobTitle" placeholder="Job Title"><br><br>
            
            <label for="jobDescription">Job Description:</label>
            <textarea id="jobDescription" name="jobDescription" placeholder="Job Description"></textarea><br><br>
            
            <input type="submit" name="editUserForm" value="Submit">
        </form>
    </div>
    <div class="hidden-form" id="changePasswordForm">
        <button onclick="toggleForm('changePasswordForm')">Close</button>
        <h2>Change Password</h2>
        <form method="POST">
            <label for="userPasswordId">User ID:</label>
            <input type="text" name="userPasswordId" id="userPasswordId" placeholder="ID" readonly><br>

            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required><br>
            
            <label for="confirmNewPassword">Confirm New Password:</label>
            <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm New Password" required><br>
            
            <input type="submit" name="changePasswordForm" value="Submit">
        </form>
    </div>
    <div id="usersList">
        
    </div>
</div>

<div>
    <h1>Announcement Moderation</h1>
    <button id="addAnnouncementButton" onclick="toggleForm('addAnnouncementForm')">Add Announcement</button>
    <div class="search-bar">
        <input type="text" id="searchAnnouncementInput" placeholder="Search Announcement">
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

    <div id="announcementsList">
        
    </div>
</div>

<br><br>
<div>
    <h1>Forum Moderation</h1>
    <button id="addForumTypeButton" onclick="toggleForm('addForumTypeForm')">Add Forum Topic</button><br/>
    <div class="search-bar">
        <input type="text" id="searchForumsPostInput" placeholder="Search Forum Posts">
    </div>

    <div id="addForumTypeForm" class="hidden-form">
        <h2>Add a Topic</h2>
        <form method="POST">
            <label for="forumTopicType">Topic:</label>
            <input type="text" name="forumTopicType" id="forumTopicType" placeholder="Topic" required><br>
            
            <input type="submit" name="addForumTypeForm" value="Submit">
            <button type="button" onclick="toggleForm('addForumTypeForm')">Cancel</button>
        </form>
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

    <div id="forumsList">
        
    </div>
</div>

<br><br>
<div>
    <h1>Logs Moderation</h1>
    <div class="search-bar">
        <input type="text" id="searchLogsInput" placeholder="Search Logs">
    </div>
    <div id="editLogForm" class="hidden-form">
        <h2>Edit Logs</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="logId">ID:</label>
            <input type="text" name="logId" id="logId" placeholder="ID" readonly><br>
            <label for="logTableName">Table Name:</label>
            <input type="text" name="logTableName" id="logTableName" placeholder="Table Name" required><br>
            <label for="logUserId">User ID:</label>
            <input type="text" name="logUserId" id="logUserId" placeholder="ID" readonly><br>
            <textarea name="logDescription" id="logDescription" rows="6" cols="100" maxlength="5000"></textarea><br>

            <input type="submit" name="editLogForm" value="Submit">
            <button type="button" onclick="toggleForm('editLogForm')">Cancel</button>
        </form>
    </div>
    <div id="logsList">
        
    </div>
</div>

<br><br>
<div>
    <h1>Active Users List</h1>
    <button id="showActiveUsersButton" onclick="toggleForm('activeUsersList')">Show</button>
    <div id="activeUsersList" class="hidden-form">
        There are currently no active users found.
    </div>
</div>

<br><br><br><br>
<div>
    Comments Moderation (Soon&trade;)
</div>

<div>
    Tickets Moderation with visuals (Soon&trade;)
</div>

<div>
    Forms Moderation with visuals (Soon&trade;)
</div>
<br><br><br>
<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("admin-dashboard");
    import_js("alert");
    import_ckEditor([
        ["body", 5000], 
        ["editBody", 5000],
        ["userDescription", 2500],
        ["jobDescription", 2500],
        ["forumPostBody", 5000],
        ["addForumPostBody", 5000]
    ]);
    import_js("announcement");
    import_js("forum");
?>