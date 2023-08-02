<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("history");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "history";
?>

<div class="container">
    <div class="image-section">
      <img src="./assets/images/pages/homepage/stacruz.jpg" alt="Image">
    </div>
    <div class="text-section">
      <h1 class="header-history">THE HISTORY OF <br>
        BARANGAY STA. CRUZ</h1>
      <p class="description">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
        esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      </p>
      <p class="description">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
        veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
        esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
        non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      </p>
    </div>
  </div>

  <hr style="height: 5px; border: 5px; background-color: #000;">

  <div class="container">
    <div class="vision">
      <h2 class="vision-title">VISION</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, <br>
        sed do eiusmod tempor incididunt ut labore et dolore <br>
        magna aliqua. Ut enim ad minim veniam, quis nostrud <br>
        exercitation ullamco laboris nisi ut aliquip ex ea commodo <br>
        consequat. Duis aute irure dolor in reprehenderit in <br>
        voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br>
        Excepteur sint occaecat cupidatat non proident, sunt in <br>
        culpa qui officia deserunt mollit anim id est laborum. <br>
      </p>
    </div>
    <div class="circle-mask">
      <img src="./assets/images/pages/homepage/logo.jpg" alt="Circle Image">
    </div>
    <div class="mission">
      <h2 class="mission-title">MISSION</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, <br>
        sed do eiusmod tempor incididunt ut labore et dolore<br>
        magna aliqua. Ut enim ad minim veniam, quis nostrud<br>
        exercitation ullamco laboris nisi ut aliquip ex ea commodo<br>
        consequat. Duis aute irure dolor in reprehenderit in<br>
        voluptate velit esse cillum dolore eu fugiat nulla pariatur.<br>
        Excepteur sint occaecat cupidatat non proident, sunt in<br>
        culpa qui officia deserunt mollit anim id est laborum.<br>
      </p>
    </div>
  </div>

  <h2 class="text-below">STA. CRUZ MAKATI</h2>

  <hr style="height: 5px; border: 5px; background-color: #000;">

  <div class="container2" align="center">
    <br>
    <h1 class="head">Hanay ng mga Punong Barangay</h1>

<div class="box1">
  <img src="./assets/images/pages/homepage/box1.png"   height="110%" align="left">
  <h2 class="name">NAME</h2>
  <h3 class="year">2018 - Present</h3>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
    sed do eiusmod tempor incididunt ut labore et dolore
    magna aliqua. Ut enim ad minim veniam, quis nostrud
    exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in
    voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    Excepteur sint occaecat cupidatat non proident, sunt in
    culpa qui officia deserunt mollit anim id est laborum.</p>
</div>

<br><br>

<div class="box2">
  <img src="./assets/images/pages/homepage/box2.png"   height="110%" align="right">
  <h2 class="name2">NAME</h2>
  <h3 class="year2">2018 - Present</h3>
  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
    sed do eiusmod tempor incididunt ut labore et dolore
    magna aliqua. Ut enim ad minim veniam, quis nostrud
    exercitation ullamco laboris nisi ut aliquip ex ea commodo
    consequat. Duis aute irure dolor in reprehenderit in
    voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    Excepteur sint occaecat cupidatat non proident, sunt in
    culpa qui officia deserunt mollit anim id est laborum.</p>
</div>

<br><br>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("history");
    import_js("alert");
?>
