<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("user-page");
    import_css("alert");

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "user";

    if(!isset($user['user_id'])){

        header("Location: login");
        
    }

    $userProfile = checkUserProfile();

    if(!isset($userProfile['user_profile_id'])){

        header("Location: login");
        
    }

    include_once('entities/user/user-controller.php');
    include_once('entities/user/user-model.php');
    include_once('utilities/validation/server/user-input-validation.php');

    include_once('entities/file/file-controller.php');
    include_once('entities/file/file-model.php');
    include_once('utilities/validation/server/file-input-validation.php');

    //Import Alert
    include_once('components/alert/alert.php');

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

    // Check for the login status and show the alert if successful
    if (isset($_SESSION['login_success']) && 
        $_SESSION['login_success'] &&
        isset($_SESSION['alert_message']) &&
        isset($_SESSION['alert_type'])
    ) {

        showAlert($_SESSION['alert_message'],  $_SESSION['alert_type']);
        unset($_SESSION['login_success']);
        unset($_SESSION['alert_message']);
        unset($_SESSION['alert_type']);

    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $logCreate = new LogCreate();
        $logCreate->tableName = 'users';
        $logCreate->dateCreated = new DateTime();
        $logCreate->userId = null;

        updateUserAndProfile($user, $userProfile, $logCreate->description, $logCreate->userId);
        createLog($logCreate);

        $user = checkUserLogin();
        $userProfile = checkUserProfile();
        
    }

?>
<br>

<div id="userProfile" align="center" class="profile">
    <div class="ban"><?php echo "<img src='". $userProfile['banner_image_url'] . "' width='790px' height='255px'/>" ?><br/></div>
    <div class="prof"><?php echo "<img src='". $userProfile['profile_image_url'] . "' width='200px' height='200px'/>" ?><br></div>
     <div class="nameused">@<?php echo $user['username'] ?><br><br></div>
    <div class="desc">BIO:<?php echo htmlspecialchars_decode($userProfile['description']); ?><br></div>
    <div class="job">JOB DESCRIPTION:<?php echo htmlspecialchars_decode($userProfile['job_description']); ?><br></div>
    <div class="bts">
    <button onclick="toggleForm('editUserForm')" class="edit">Edit Profile</button>
    <button onclick="toggleForm('changePasswordForm')" class="change">Change Password</button>
    </div>
</div>
</br>

<div class="hidden-form1" id="editUserForm" align="center">
    <h2>Edit Profile Form</h2></br>
    <form method="POST" enctype="multipart/form-data">
        <label for="profileImage">Add Profile Picture (Max 2MB):</label>
        <img src="#" id="previewProfileImage" alt="Preview Profile Image" style="max-width: 200px; max-height: 200px; display: none;">
        <input type="file" name="profileImage" id="profileImage" accept="image/*"><br>

        <label for="profileBanner">Add Profile Banner (Max 5MB):</label>
        <img src="#" id="previewProfileBanner" alt="Preview Profile Banner" style="max-width: 970px; max-height: 250px; display: none;">
        <input type="file" name="profileBanner" id="profileBanner" accept="image/*"><br><br>

        <label for="userDescription">User Bio:</label>
        <textarea id="userDescription" name="userDescription" placeholder="User Description"><?php echo $userProfile['description']; ?></textarea><br><br>


        <div class="edituser" align="left">
        <label for="username">Username:</label></br>
        <input class="boxuser" type="text" id="username" name="username" value="<?php echo $user['username']; ?>" placeholder="Username" required><br><br>
        </div>

        <div class="editemail" align="left">
        <label for="email">Email:</label></br>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" required><br><br>
        </div>

        <div class="editfirst" align="left">
        <label for="firstName">First Name:</label></br>
        <input type="text" id="firstName" name="firstName" value="<?php echo $userProfile['first_name']; ?>" placeholder="First Name" required><br><br>
        </div>

        <div class="editlast" align="left">
        <label for="lastName">Last Name:</label></br>
        <input type="text" id="lastName" name="lastName" value="<?php echo $userProfile['last_name']; ?>" placeholder="Last Name" required><br><br>
        </div>

        <div class="editbirth" align="left">
        <label for="birthDate">Birth Date:</label></br>
        <input type="date" id="birthDate" name="birthDate" value="<?php echo $userProfile['birth_date']; ?>" placeholder="Birth Date" required><br><br>
        </div>

        <div class="editadd" align="left">
        <label for="address1">Address:</label></br>
        <input type="text" id="address1" name="address1" placeholder="Address Line 1" value="<?php echo isset($userProfile['address']) ? explode(', ', $userProfile['address'], 5)[0] : null; ?>" required><br>
        <input type="text" id="address2" name="address2" placeholder="Address Line 2" value="<?php echo isset($userProfile['address']) ? explode(', ', $userProfile['address'], 5)[1] : null; ?>"><br><br>
        </div>
        
        <div class="editcity" align="left">
        <label for="city">City:</label></br>
        <input type="text" id="city" name="city" placeholder="City" value="<?php echo isset($userProfile['address']) ? explode(', ', $userProfile['address'], 5)[2] : null; ?>" required><br><br>
        </div>

        <div class="editstate" align="left">
        <label for="stateProvince">State/Province:</label></br>
        <input type="text" id="stateProvince" name="stateProvince" placeholder="State / Province" value="<?php echo isset($userProfile['address']) ? explode(', ', $userProfile['address'], 5)[3] : null; ?>" required><br><br>
        </div>

        <div class="editzip" align="left">
        <label for="zipCode">Zip Code:</label></br>
        <input type="text" id="zipCode" name="zipCode" placeholder="Zip Code" value="<?php echo isset($userProfile['address']) ? explode(', ', $userProfile['address'], 5)[4] : null; ?>" required><br><br>
        </div>

        <div class="editgender" align="left">
        <label for="gender">Gender:</label></br>
        <select name="gender" id="gender" required>
            <option value="Male" <?php echo $userProfile['gender'] === 'Male' ? ' selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo $userProfile['gender'] === 'Female' ? ' selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo $userProfile['gender'] === 'Other' ? ' selected' : ''; ?>>Other</option>
        </select><br><br>
        </div>
        
        <div class="editnum" align="left">
        <label for="phoneNumber">Phone Number:</label></br>
        <input type="text" id="phoneNumber" name="phoneNumber" value="<?php echo $userProfile['phone_number']; ?>" placeholder="Phone Number"><br><br>
        </div>
        
        <div class="edittitle" align="left">
        <label for="jobTitle" align="left">Job Title:</label></br>
        <input type="text" id="jobTitle" name="jobTitle" value="<?php echo $userProfile['job_title']; ?>" placeholder="Job Title"><br><br>
        </div>

        <div class="editdesc">
        <label for="jobDescription" align="left">Job Description:</label></br>
        <textarea id="jobDescription" name="jobDescription" placeholder="Job Description"><?php echo $userProfile['job_description']; ?></textarea><br><br>
        </div>

        <input type="submit" name="editUserForm" value="Submit" class="editsub">
    </form>
</div>
</br>



<div class="hidden-form2" id="changePasswordForm" align="center">
    <h2 class="cp">Change Password</h2>
    <form method="POST">
        <label for="oldPassword">Old Password:</label></br>
        <input type="password" id="oldPassword" name="oldPassword" placeholder="Old Password" required><br><br>

        <label for="newPassword">New Password:</label></br>
        <input type="password" id="newPassword" name="newPassword" placeholder="New Password" required><br><br>
        
        <label for="confirmNewPassword">Confirm New Password:</label></br>
        <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm New Password" required><br><br>
        
        <input type="submit" name="changePasswordForm" value="Submit" class="subpass">
    </form>
</div>
<br>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("user-page");
    import_js("alert");
    import_ckEditor([
        ["userDescription", 2500],
        ["jobDescription", 2500]
    ]);
?>