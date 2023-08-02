<?php 
    // Imports CSS
    require('utilities/validation/server/css-validation.php');
    import_css("services");
    import_css("alert");

    // Import Alert
    include_once('components/alert/alert.php');

    // Checks if the user is logged in before
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
    $_SESSION['current_page'] = "services";

    // Array of forms and their corresponding PDF filenames
    $forms = array(
        "Form 1" => "Form1.pdf",
        "Form 2" => "Form2.pdf",
        "Form 3" => "Form3.pdf"
        // Add more forms here as needed
    );
?>

<div class="container" align="center">
    <h1 class="head">BARANGAY SERVICES</h1> 
</div>

<div class="center" align="center">
    <div class="card1">
        <div class="ser1">
    </div>
        <h1 class="title">Services</h1>
        <a href="#"><button class="learn-more-btn">Learn More</button></a>
    </div>

    <div class="card2">
        <div class="ser2">
    </div>
        <h1 class="title">Services</h1>
        <a href="#"><button class="learn-more-btn">Learn More</button></a>
        </div>

    <div class="card3">
        <div class="ser3">
    </div>
        <h1 class="title">Services</h1>
        <a href="#"><button class="learn-more-btn">Learn More</button></a>
        </div>

    <div class="card4">
        <div class="ser4">
    </div>
        <h1 class="title">Services</h1>
        <a href="#"><button class="learn-more-btn">Learn More</button></a>
    </div> 
    
    <br>

<div class="df">
    <h2 class="dftxt">DOWNLOADABLE FORMS</h2>
    <div>
    <ul>
        <?php foreach ($forms as $formName => $pdfFilename): ?>
            <li>
                <a class="download-link" href="./assets/files/pages/services/pdfs/<?php echo $pdfFilename; ?>" download="<?php echo $pdfFilename; ?>">
                    <?php echo $formName; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
</div>

<br><br><br><br>

<div class="df2">
    <h2 class="dftxt2">WHAT ARE YOU APPLYING FOR?</h2>
</div>

</div> 

<br><br>

<div class="content-buttom">
    <br><br>
    <h1 class="NA" align="center">NEED ASSISTANCE?</h1>
    <h2 class="CU" align="center">Contact us at</h2>
    <div class="contact-us" align="center">
        <p>XXXXXXXXXXX.com</p>
        <p>xxxx-xxx-xxxx</p>
        </div>
    <br>
</div>

<div class="content-buttom2" align="center">
    <br><br><br><br><br>
    <div class="content-buttom2-1">
    <h1>DOWNLOADABLE FORMS</h1>
    <h1>ONLINE BARANGAY ID SERVED</h1>
    <h1>ONLINE BUSINESS PERMIT SERVED</h1>
    <h1>NEW CLEARANCES</h1>
  </div>
</div>


<?php 
    // Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("services");
    import_js("alert");
?>
