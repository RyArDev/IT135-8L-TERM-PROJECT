function closeAlert() {

    const alertContainer = document.getElementById("alertContainer");

    if (alertContainer) {

        alertContainer.remove();

    }
}
// Function to close the alert after 5 seconds
function autoCloseAlert() {

    const timeBar = document.getElementById('timeBar');
    let width = 100;

    const interval = setInterval(function () {

        if (width <= 0) {

            clearInterval(interval);
            closeAlert();

        } else {

            width--;
            timeBar.style.width = width + '%';
        }

    }, 50); // Adjust the speed of the time bar here (lower value makes it faster)
}

// Call the autoCloseAlert function when the alert is shown
function init(){

    document.addEventListener('DOMContentLoaded', function () {

        const alertContainer = document.getElementById('alertContainer');

        if (alertContainer) {
            
            // Remove the auto-close behavior
            alertContainer.removeEventListener("DOMContentLoaded", autoCloseAlert());
        }

    });

}

init();