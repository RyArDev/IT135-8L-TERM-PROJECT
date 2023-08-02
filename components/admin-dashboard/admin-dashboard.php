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

    function updateUserAndProfile($user, $userProfile, &$logDescription, &$logUserId){

        $message = "";
        $type = "";

        if (isset($_POST["editUserForm"])) {

            $userProfileErrors = array();
            $profileImageErrors = array();

            $userEdit = new UserEdit();
            $userEdit->userId = $user['user_id'];
            $userEdit->username = isset($_POST['username']) ? $_POST['username'] : null;
            $userEdit->email = isset($_POST['email']) ? $_POST['email'] : null;

            $userProfileEdit = new UserProfileEdit();
            $userProfileEdit->userProfileId = $userProfile['user_profile_id'];
            $userProfileEdit->firstName = isset($_POST['firstName']) ? $_POST['firstName'] : null;
            $userProfileEdit->lastName = isset($_POST['lastName']) ? $_POST['lastName'] : null;
            $userProfileEdit->birthDate = isset($_POST['birthDate']) ? $_POST['birthDate'] : null;
            $userProfileEdit->userId = $user['user_id'];

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

            $userErrors = validateUser($userEdit);
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

            $userUpdateSuccess = updateUser($userEdit);
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

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $logCreate = new LogCreate();
        $logCreate->dateCreated = new DateTime();
        $logCreate->userId = null;
        
        if (isset($_POST["addAnnouncementForm"])) {

            $logCreate->tableName = 'announcements';
            addAnnouncement($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["editAnnouncementForm"])) {

            $logCreate->tableName = 'announcements';
            editAnnouncement($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST["deleteAnnouncementForm"])) {

            $logCreate->tableName = 'announcements';
            deleteAnnouncement($user, $logCreate->description, $logCreate->userId);

        }

        if (isset($_POST['editUserForm']) || isset($_POST['changePasswordForm'])){

            $logCreate->tableName = 'users';
            updateUserAndProfile($user, $userProfile, $logCreate->description, $logCreate->userId);

        }

        createLog($logCreate);
        $announcements = getAllAnnouncements();
        
    }

?>

<div>
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

<div>
    <h1>Forum Moderation</h1>
</div>

<div>
    <h1>Logs Moderation</h1>
    <div class="search-bar">
        <input type="text" id="searchLogsInput" placeholder="Search Logs">
    </div>
    <div id="logsList">
        
    </div>
</div>

<div>
    <h1>Active Users List</h1>
    <button id="showActiveUsersButton" onclick="toggleForm('activeUsersList')">Show</button>
    <div id="activeUsersList" class="hidden-form">
        There are currently no active users found.
    </div>
</div>

<div>
    Comments Moderation (Soon&trade;)
</div>

<div>
    Tickets Moderation with visuals (Soon&trade;)
</div>

<div>
    Forms Moderation with visuals (Soon&trade;)
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("admin-dashboard");
    import_js("alert");
    import_ckEditor([
        ["body", 5000], 
        ["editBody", 5000],
        ["userDescription", 2500],
        ["jobDescription", 2500]
    ]);
    import_js("announcement");
?>