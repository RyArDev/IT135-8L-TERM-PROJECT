<?php
    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $userProfile = checkUserProfile();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="Description of your website">
        <meta name="keywords" content="keyword1, keyword2, keyword3">
        <meta name="author" content="Your Name">
        <title>Your Website Title</title>
        <link rel="icon" href="favicon.ico" type="image/ico">
        <link rel="stylesheet" type="text/css" href="global.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    </head>
    <body>

    <div class="header">
    <div class="navigation-bar">
      <div class="logo">
        <a href="#">
          <img src="./assets/logo.jpg" alt="Logo">
        </a>      <div class="circle"></div>
        <span class="logo-text">STA. CRUZ MAKATI</span>
      </div>
  
      <div class="navigation-links">
        <a href="home">Home</a>
        <a href="services">Services</a>
        <a href="announcement">Announcements</a>
        <a href="history">History</a>
        <a href="forums">Forum</a>
        <a href="contact-us">Contact Us</a>
      </div>

      <?php
    if(isset($_SESSION['user_id'])){

        echo 
        "<a href='/profile'>" . $user['username'] .
            "<img src='". $userProfile['profile_image_url'] . "'alt='User Avatar' class='avatar-icon' width='10%' align='right'/>
        </a>";

    }else{

        echo "<a href='/login'>Login</a><a href='/register'>Register</a>";

    }
?>
    </div>
  </div>


  </div>
</div>

        <div class="page-content">
            <?php 
                if(isset($page) && file_exists($page)) {

                    require($page); 

                }else{

                    require($errorPage);

                }
            ?>
        </div>

        <div class="footer">
    <div class="footer-left">
      <p><br>STA. CRUZ MAKATI</p>
    <div class="contact">
      <p>XXXXXXXXXXX</p>
      <p>XXXXXXXXXXX</p>
      <p>XXXXXXXXXXX</p>
      </div>
    </div>
    <div class="footer-center">
      <div class="socials-container">
        <p class="socials"><br><br>Social Links</p>
        <div class="icons">
          <a href="https://www.facebook.com/stacruzmakatiofficials" target="facebook">
            <img src="./assets/images/pages/homepage/facebook.png" class="icon">
          </a>
          <a href="https://www.makati.gov.ph/" target="website">
            <img src="./assets/images/pages/homepage/globe.png" class="icon">
          </a>
          <a href="#" target="email">
            <img src="./assets/images/pages/homepage/email.png" class="icon">
          </a>
          <a href="https://www.youtube.com/@barangaysantacruzmakati1530" target="youtube">
            <img src="./assets/images/pages/homepage/youtube.png" class="icon">
          </a>
        </div>
        <p class="all-rights">© All Rights Reserved 2023</p>
      </div>
    </div>
    <div class="footer-right">
      <p>Quick Links</p>
    <div class="links">
      <a href="https://www.makati.gov.ph/">Makati Web Portal </a><br>
      <a href="#">Barangay Services </a><br>
      <a href="#">Hotlines </a><br>
    </div>
    </div>    
    </div>
    </div>
  </div>
  
    </body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.min.js" integrity="sha512-/F8YhC3n5OrM9ta9htMD620kH0paKnjDHCHcSvyWumxlqsnkS/XCpYExuMZuXE4K3GE9tDQFBqgXsmkjsjRbDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.sync.min.js" integrity="sha512-WGR8mp0bTMUqHRjk/FmZ6jFraoRFWJGXtEdj5p1PZmGxVWPPb5y4CIj96O8cFpigsE1cuR7Y0w2oTCMuk5nY0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.worker.min.js" integrity="sha512-HDxgoK5C789rI2GQb7GzGO5uUOWPQPumO816JFI4QLKr83eC+s9eUF9MOuYGqZOdD8v5JMMkrWZ+622UJ4sG4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <script src="utilities/validation/client/user-status-validation.js"></script>
</html>