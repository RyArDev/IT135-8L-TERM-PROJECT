<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("barangay-corner");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "barangay-corner";
?>

<div class="content" align="center">

            <div class="PB">
            <h1>BARANGAY OFFICE'S CORNER</h1>
                <img src="./assets/images/pages/homepage/self.png" alt="photo1" height="200px" align="center">
            </div> 

            <div class="PB2">
            <h1>LEGISLATIVE BRANCH</h1>
                <img src="./assets/images/pages/homepage/self.png" alt="photo1" height="200px" align="left">
            </div>
</div>


<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("barangay-corner");
    import_js("alert");
?>