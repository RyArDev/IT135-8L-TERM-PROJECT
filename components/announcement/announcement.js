function toggleForm(formId) {

    let form = document.getElementById(formId);
    let otherForms = document.getElementsByClassName("hidden-form");

    for (let i = 0; i < otherForms.length; i++) {

        if (otherForms[i] !== form) {

            otherForms[i].style.display = "none";
            
        }

    }

    form.style.display = form.style.display === "block" ? "none" : "block";

}

function getEditAnnouncementButtons(){

    const editAnnouncementButtons = document.querySelectorAll(".editAnnouncementButton");

    editAnnouncementButtons.forEach(button => {

        button.addEventListener("click", function() {

            document.getElementById("editId").value = this.getAttribute("data-id");
            document.getElementById("editTitle").value = this.getAttribute("data-title");
            document.getElementById("editType").value = this.getAttribute("data-type");
            
            const editBodyEditor = ckEditorInstances['editBody'];
            
            if (editBodyEditor) {

                editBodyEditor.setData(this.getAttribute("data-body"));

            }

        });

    });
    
}

function initEditAnnouncement() {

    document.addEventListener("DOMContentLoaded", function() {

        getEditAnnouncementButtons();

    });

}

initEditAnnouncement();