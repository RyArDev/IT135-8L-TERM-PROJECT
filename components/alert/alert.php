<?php 
    function showAlert($message, $type) {

        echo '<div class="alert-container" id="alertContainer">'
        . '<div class="alert-box ' . ($type == 'success' ? 'success' : 'error') . '" id="alertBox">'
        . '<span class="close-btn" id="closeBtn" onclick="closeAlert()">&times;</span>'
        . '<h1>' . ($type == 'success' ? 'Success' : 'Error') . '</h1>'
        . '<p id="alertMessage">' . $message . '</p>'
        . '<div class="time-bar" id="timeBar"></div>'
        . '</div>'
        . '</div>';
        
    }
?>