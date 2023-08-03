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

// Priority options mapping
$priorityOptions = array(
    1 => "Low",
    2 => "Medium",
    3 => "High",
    4 => "Urgent"
);


?>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $subject = $_POST["subject"];
    $priority_id = $_POST["priority_id"];
    $message = $_POST["message"];


    $conn = new mysqli("localhost", "root", '', "barangay_sta_cruz");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = null;
    $find_user_query = "SELECT user_id FROM users WHERE email = '$email'";
    $user_result = $conn->query($find_user_query);
    if ($user_result->num_rows > 0) {
        $user_data = $user_result->fetch_assoc();
        $user_id = $user_data["user_id"];
    } else {

        echo "User not found. Please make sure you have a valid email registered.";
        $conn->close();
        exit; 
    }

 
    $priority_options = array(
        1 => "low",
        2 => "medium",
        3 => "high",
        4 => "urgent"
    );

    $priority_name = $priority_options[$priority_id];


    $status_id = 1;


    $insert_ticket_query = "INSERT INTO tickets (title, body, date_created, date_modified, user_id, status_id, priority_id) 
                            VALUES ('$subject', '$message', NOW(), NOW(), '$user_id', '$status_id', '$priority_id')";

    if ($conn->query($insert_ticket_query) === TRUE) {

        echo "Ticket successfully submitted!";
    } else {

        echo "Ticket submission failed. Please try again later.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<body>
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

        <form method="POST" action="">
            <div class="contact-section" align="center">
                <div class="left-section">
                    <div class="info">
                        <h3>Name<span class="required-asterisk">*</span></h3>        
                        <span class="icon"><img src="./assets/images/pages/homepage/account.png"></span>
                        <input type="text" name="name" placeholder="Name" required>
                    </div>

                    <div class="info">
                        <h3>Email<span class="required-asterisk">*</span></h3>
                        <span class="icon2"><img src="./assets/images/pages/homepage/email2.png"></span>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>

                    <div class="info">
                        <h3>Address<span class="required-asterisk">*</span></h3>
                        <span class="icon"><img src="./assets/images/pages/homepage/home.png" ></span>
                        <input type="text" name="address" placeholder="Residential Address" required>
                    </div>
                </div>

                <div class="right-section">
                    <div class="info">
                        <h3>Phone Number</h3>
                        <span class="icon"><img src="./assets/images/pages/homepage/phone-call.png"></span>
                        <input type="tel" name="phone" placeholder="+63-xxx-xxx-xxxx">
                    </div>

                    <div class="info">
                        <h3>Subject<span class="required-asterisk">*</span></h3>
                        <input type="text" name="subject" placeholder="Subject" required>
                    </div>

                    <div class="info">
                        <h3>Priority</h3>
                        <select name="priority_id">
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Urgent</option>
                        </select>
                    </div>

                    <div class="info">
                        <h3>Message</h3>
                        <textarea name="message" placeholder="Type your message here..." required></textarea>
                    </div>

                    <button type="submit">Submit</button>
                </div>

                <div style="clear: both;"></div>
            </div>
        </form>
    </div>

</html>
