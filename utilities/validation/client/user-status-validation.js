/*
Long Polling Method

    function checkUserActivity() {

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'entities/user/user-controller.php?action=checkUserActivity', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send();

        xhr.onload = function () {

            setTimeout(checkUserActivity, 10 * 1000); //10 seconds

        };

        xhr.onerror = function () {

            xhr.open('POST', 'entities/user/user-controller.php?action=setUserInactive', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send();

        };

    }

    checkUserActivity();

    window.addEventListener('beforeunload', function () {

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'entities/user/user-controller.php?action=setUserInactive', false);
        xhrInactive.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhrInactive.send();
        console.log('beforeunload event triggered!');

    });

*/

window.addEventListener('load', function() {

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'entities/user/user-controller.php?action=checkUserActivity', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();

});

window.addEventListener('beforeunload', function () {

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'entities/user/user-controller.php', false);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('action=setUserInactive');

});

