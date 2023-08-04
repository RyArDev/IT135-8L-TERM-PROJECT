<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("homepage");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');
    
    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "homepage";
?>
    
  <div>
   <div class="hero-gallery">
    <div class="slider">
      <img src="./assets/images/pages/homepage/image1.jpg" alt="Image 1">
      <img src="./assets/images/pages/homepage/image2.jpeg" alt="Image 2">
      <img src="./assets/" alt="Image 3">
      <img src="./assets/" alt="Image 4">
    </div>

    <div class="hero-overlay"></div>
    <div class="hero-text">
      <h1 class="hero-heading">WELCOME TO THE BARANGAY OF STA. CRUZ</h1>
 
    </div>
    <div class="dots">
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>
  </div>
  
<div class="barangay-services">
  BARANGAY SERVICES
</div>

<div class="carousel-frame">
<div class="card-container">
  <!-- First card -->
  <div class="card">
    <div class="avatar">
      </div>    
    <h1>Lost & Found</h1>
    <a href="#"><button class="learn-more-btn">Learn More</button></a>
  </div>

  <!-- Second card -->
  <div class="card">
    <div class="avatar1">
      <!-- avatar icon  -->
    </div>
    <h1>Barangay Events</h1>
    <a href="#"><button class="learn-more-btn">Learn More</button></a>
  </div>

  <!-- Third card -->
  <div class="card">
    <div class="avatar2">
      <!-- avatar icon  -->
    </div>
    <h1>Job Opportunities</h1>
    <a href="#"><button class="learn-more-btn">Learn More</button></a>
  </div>

      <!-- Third card -->
      <div class="card">
        <div class="avatar3">
        </div>
        <h1>Card Three</h1>
        <a href="#"><button class="learn-more-btn">Learn More</button></a>
      </div>

          <!-- Third card -->
  <div class="card">
    <div class="avatar4">
    </div>
    <h1>Card Three</h1>
    <a href="#"><button class="learn-more-btn">Learn More</button></a>
  </div>

      <!-- Third card -->
      <div class="card">
        <div class="avatar5">
        </div>
        <h1>Card Three</h1>
        <a href="#"><button class="learn-more-btn">Learn More</button></a>
      </div>
  

</div>


<button class="arrow-btn left-arrow">←</button>
<button class="arrow-btn right-arrow">→</button>

<div class="main-container">
<div class="image-container">
    <img src="./assets/images/pages/homepage/barangay-corner.png" class="image"  alt="Barangay Office's Corner"/>
</div>

<div class="content-container">
    <p class="corner-text">THE BARANGAY OFFICE'S CORNER</p>
    <a href="barangay"><button class="corner-button">GO TO BARANGAY OFFICE'S CORNER</button></a>
</div>
</div>

    <div class="hero-gallery">
      <div class="slider">

        <img src="./assets/" alt="Image 1">
      
  
      <div class="hero-overlay2"></div>
      <div class="hero-text">
        <h1 class="hero-heading">THE FORUM</h1>
        <p class="hero-paragraph">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
          veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit
          esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
          laborum."</p>

          <a href="#"><button class="learn-more-btn">Learn More</button></a>
      </div>
    </div>
  </div>

  <hr style="height: 10px; border: 5px; background-color: #000;">

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("homepage");
    import_js("alert");
?>