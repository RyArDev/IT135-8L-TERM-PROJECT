const onChangeListener = previewProfileImage;
const isListenerAttached = (element, eventType, listener) => {
    const listeners = element.getEventListeners?.(eventType) || [];
    return listeners.some((event) => event.listener === listener);
};

function toggleForm(formId) {

    var form = document.getElementById(formId);

    var otherForms = document.getElementsByClassName("hidden-form");

    //console.log(isListenerAttached(document.getElementById("profileImage"), "change", onChangeListener));

    for (var i = 0; i < otherForms.length; i++) {

        if (otherForms[i] !== form) {

            otherForms[i].style.display = "none";

            if (form == "profileImage"){

                document.getElementById("profileImage").removeEventListener("change", previewProfileImage);

            }
        }

    }

    form.style.display = form.style.display === "block" ? "none" : "block";

}

function previewProfileImage(event) {

    const input = event.target;

    //console.log(isListenerAttached(document.getElementById("profileImage"), "change", onChangeListener));

    if (input.files && input.files[0]) {

        const reader = new FileReader();

        reader.onload = function (e) {

            const preview = document.getElementById("previewProfileImage");
            preview.src = e.target.result;
            preview.style.display = "block";

        };

        reader.readAsDataURL(input.files[0]);

    }

}

function previewProfileBanner(event) {

    const input = event.target;

    //console.log(isListenerAttached(document.getElementById("profileImage"), "change", onChangeListener));

    if (input.files && input.files[0]) {

        const reader = new FileReader();

        reader.onload = function (e) {

            const preview = document.getElementById("previewProfileBanner");
            preview.src = e.target.result;
            preview.style.display = "block";

        };

        reader.readAsDataURL(input.files[0]);

    }

}

function init(){

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("profileImage").addEventListener("change", previewProfileImage);
        document.getElementById("profileBanner").addEventListener("change", previewProfileBanner);
    });

}

init();