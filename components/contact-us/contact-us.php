<?php 
    //Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("contact-us");
    import_css("alert");

    //Import Alert
    include_once('components/alert/alert.php');

    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "contact-us";
    
?>

<div class="contact-section" align="center">
        <h1 class="contact-emergency">CONTACT INFORMATION</h1>
        <div class="centered-image" align="center">
            <h2 class="title">Barangay Sta. Cruz</h2>
      </div>

      <br>

      <hr style="height: 5px; border: 5px; background-color: #000;">
      <br>

      <div class="contact-information-section" align="center">
        <h1 class="contact-information-header">CONTACT INFORMATION</h1>
    </div>

  <div class="contact-rectangle">
    <div class="left-section">
      <div class="info">
        <h3>Name<span class="required-asterisk">*</span></h3>        
        <span class="icon"><img src="./assets/images/pages/homepage/account.png"></span>
        <input type="text" placeholder="Name" required>
      </div>

      <div class="info">
        <h3>Email<span class="required-asterisk">*</span></h3>
        <span class="icon2"><img src="./assets/images/pages/homepage/email2.png"></span>
        <input type="email" placeholder="Email" required>
      </div>

      <div class="info">
        <h3>Address<span class="required-asterisk">*</span></h3>
        <span class="icon"><img src="./assets/images/pages/homepage/home.png" ></span>
        <input type="text" placeholder="Residential Address" required>
      </div>
    </div>

    <div class="right-section">
      <div class="info">
        <h3>Phone Number</h3>
        <span class="icon"><img src="./assets/images/pages/homepage/phone-call.png"></span>
        <input type="tel" placeholder="+63-xxx-xxx-xxxx">
    </div>

      <div class="info">
        <h3>Message</h3>
        <textarea placeholder="Type your message here..."></textarea>
      </div>
    </div>
    <div style="clear: both;"></div>
  </div>

  <br><br>

  <a href="#" class="submit-button">SUBMIT</a>
  
  <br><br><br><br>

<?php 
    //Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("contact-us");
    import_js("alert");
?>