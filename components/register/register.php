<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("register");
    import_css("alert");

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();

    if(isset($user['user_id'])){

        header("Location: profile");
        
    }

    include_once('entities/user/user-model.php');
    include_once('entities/user/user-controller.php');

    include_once('utilities/validation/server/user-input-validation.php');

    //Import Alert
    include_once('components/alert/alert.php');
    
    function userRegister(){

        $message = "";
        $type = "";

        $userRegister = new UserRegister();
        $userRegister->username = isset($_POST['username']) ? $_POST['username'] : null;
        $userRegister->firstName = isset($_POST['firstName']) ? $_POST['firstName'] : null;
        $userRegister->lastName = isset($_POST['lastName']) ? $_POST['lastName'] : null;
        $userRegister->email = isset($_POST['email']) ? $_POST['email'] : null;
        $userRegister->password = isset($_POST['password']) ? $_POST['password'] : null;
        $userRegister->confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null;
        $userRegister->birthDate = isset($_POST['birthDate']) ? $_POST['birthDate'] : null;
        $fullAddress = "";

        if(isset($_POST['address1']) && $_POST['address1'] !== "" ){

            $fullAddress .= $_POST['address1'].", ";

        }

        if(isset($_POST['address2'])){

            $fullAddress .= $_POST['address2'].", ";

        }else{

            $fullAddress .= ", ";

        }

        if(isset($_POST['city']) && $_POST['city']!== "" ){

            $fullAddress .= $_POST['city'].", ";

        }

        if(isset($_POST['stateProvince']) && $_POST['stateProvince'] !== "" ){
            
            $fullAddress .= $_POST['stateProvince'].", ";
            
        }

        if(isset($_POST['zipCode']) && $_POST['zipCode'] !== "" ){
            
            $fullAddress .= $_POST['zipCode'];
            
        }

        $userRegister->address = $fullAddress;
        $userRegister->gender = isset($_POST['gender']) ? $_POST['gender'] : null;
        $userRegister->agreeTerms = isset($_POST['agreeTerms']) && $_POST['agreeTerms'] == 'on' ? isset($_POST['agreeTerms']) : null;

        $userRegister = sanitizeClass($userRegister);
        $userRegisterErrors = validateUserRegistration($userRegister);

        if (!empty($userRegisterErrors)) {

            $message .= "<b>User Registration Error</b><br>";

            foreach ($userRegisterErrors as $error) {

                $message .= "- $error<br>";

            }

            $type = 'error';
            showAlert($message, $type);
            return;

        }

        $registerUserSuccess = registerUser($userRegister);

        if (!$registerUserSuccess) {

            $message = 'User Registered Unsuccessfully!';
            $type = 'error';
            showAlert($message, $type);
            return;

        }

        $message = 'User Registered Successfully!';
        $type = 'success';
        
        $_SESSION['register_success'] = true;
        $_SESSION['alert_message'] = $message;
        $_SESSION['alert_type'] = $type;

        header("Location: login");

    }

    //Form Submission for Registration
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        userRegister();

    }
?>

<div>
    <h2>Registration Form</h2>
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : null; ?>" required><br><br>
        
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" placeholder="First Name" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : null; ?>" required><br><br>
        
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" placeholder="Last Name" value="<?php echo isset($_POST['lastName']) ? $_POST['lastName'] : null; ?>" required><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : null; ?>" required><br><br>
        
        <label for="birthDate">Birth Date:</label>
        <input type="date" id="birthDate" name="birthDate" placeholder="Birth Date" value="<?php echo isset($_POST['birthDate']) ? $_POST['birthDate'] : null; ?>" required><br><br>
        
        <label for="address1">Address:</label>
        <input type="text" id="address1" name="address1" placeholder="Address Line 1" value="<?php echo isset($_POST['address1']) ? $_POST['address1'] : null; ?>" required><br>
        <input type="text" id="address2" name="address2" placeholder="Address Line 2" value="<?php echo isset($_POST['address2']) ? $_POST['address2'] : null; ?>"><br><br>
        
        <label for="city">City:</label>
        <input type="text" id="city" name="city" placeholder="City" value="<?php echo isset($_POST['city']) ? $_POST['city'] : null; ?>" required><br><br>
        
        <label for="state">State/Province:</label>
        <input type="text" id="stateProvince" name="stateProvince" placeholder="State / Province" value="<?php echo isset($_POST['stateProvince']) ? $_POST['stateProvince'] : null; ?>" required><br><br>

        <label for="zipCode">Zip Code:</label>
        <input type="text" id="zipCode" name="zipCode" placeholder="Zip Code" value="<?php echo isset($_POST['zipCode']) ? $_POST['zipCode'] : null; ?>" required><br><br>

        <label for="gender">Gender:</label>
        <select name="gender" id="gender" required>
            <option value="None" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'None' ? ' selected' : ''; ?> disabled>Please pick a gender</option>
            <option value="Male" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Male' ? ' selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Female' ? ' selected' : ''; ?>>Female</option>
            <option value="Other" <?php echo isset($_POST['gender']) && $_POST['gender'] === 'Other' ? ' selected' : ''; ?>>Other</option>
        </select><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Password" required><br><br>
        
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required><br><br>

        <input type="checkbox" id="agreeTerms" name="agreeTerms">
        <span class="required" id="agreeTermsError">By clicking, you agree to our <a href="#" onclick="showTermsAndConditions()">Terms and Conditions</a>.</span><br><br>
        
        <input type="submit" value="Register">
    </form>
</div>

<div class="terms-container" id="termsContainer">
    <div class="terms-box" id="termsBox">
        <h2>Terms and Conditions</h2>
        <div class="term-content">
            <p>
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
                This is the content of the terms and conditions. Replace this with your actual terms and conditions text.
            </p>
        </div>
        <button onclick="closeTerms()">Close</button>
    </div>
</div>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("register");
    import_js("alert");
?>