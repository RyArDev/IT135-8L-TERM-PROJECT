function toggleForm(formId) {

    var form = document.getElementById(formId);

    var otherForms = document.getElementsByClassName("hidden-form");

    //console.log(isListenerAttached(document.getElementById("profileImage"), "change", onChangeListener));

    for (var i = 0; i < otherForms.length; i++) {

        if (otherForms[i] !== form) {

            otherForms[i].style.display = "none";
            
        }

    }

    form.style.display = form.style.display === "block" ? "none" : "block";

}

function initEditAnnouncement() {
    
    document.addEventListener("DOMContentLoaded", function() {
        
        const editAnnouncementButtons = document.querySelectorAll(".editAnnouncementButton");

        editAnnouncementButtons.forEach(button => {

            button.addEventListener("click", function() {

                document.getElementById("editId").value = this.getAttribute("data-id");
                document.getElementById("editTitle").value = this.getAttribute("data-title");
                document.getElementById("editType").value = this.getAttribute("data-type");
                
                const editBodyEditor = ckEditorInstances['editBody'];

                console.log(this.getAttribute("data-body"));
                
                if (editBodyEditor) {

                    editBodyEditor.setData(this.getAttribute("data-body"));

                }

            });

        });

    });

}

initEditAnnouncement();