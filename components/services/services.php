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

<div>
    <h2>Forms:</h2>
    <ul>
        <?php foreach ($forms as $formName => $pdfFilename): ?>
            <li>
                <a class="download-link" href="/assets/pdfs/<?php echo $pdfFilename; ?>" download="<?php echo $pdfFilename; ?>">
                    <?php echo $formName; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


<?php 
    // Imports Javascript
    require_once('utilities/validation/server/js-validation.php');
    import_js("services");
    import_js("alert");
?>
