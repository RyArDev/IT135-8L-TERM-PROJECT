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

function toggleForumDiv(targetDiv) {
    var divs = ['forum', 'forumTopic', 'forumPost'];

    for (var i = 0; i < divs.length; i++) {
        var div = document.getElementById(divs[i]);
        if (divs[i] === targetDiv) {
            div.style.display = 'block';
        } else {
            div.style.display = 'none';
        }
    }
}

function showForumTopic(forumType, userId, forumTypeId) {
    
    toggleForumDiv('forumTopic');
    let xhr = new XMLHttpRequest();
    xhr.open('GET', `entities/forum/forum-controller.php?action=getAllForumsByType&type=${forumType}`, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem('jwt_token'));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();

    xhr.onreadystatechange = function() {
        
        if (xhr.readyState === XMLHttpRequest.DONE) {
            
            if (xhr.status === 200) {
                
                let responseData = JSON.parse(xhr.responseText.trim());

                if(responseData.forumsByType !== null){

                    let forumTopics = `
                        <h1>${responseData.forumsByType[0].type}</h1>
                        <button class="addForumPostButton" onclick="toggleForm('addForumPostForm')"
                            data-typeId="${responseData.forumsByType[0].forum_type_id}"
                        >Add Post</button>
                        ${responseData.forumsByType.map(forum => 
                            `
                                <br/><a href="#" onclick="showForumPost(${forum.forum_id},${userId})">${forum.title}</a>
                            `
                        ).join('')}
                        <a href="#forum" onclick="toggleForumDiv('forum');">Back to Forum</a>`;
                    document.getElementById('forumTopic').innerHTML = forumTopics;
                
                }else{

                    document.getElementById('forumTopic').innerHTML = `
                        <h1>${forumType}</h1>
                        <br/><button class="addForumPostButton" onclick="toggleForm('addForumPostForm')"
                            data-typeId="${forumTypeId}"
                        >Add Post</button>
                        <br/>No forum posts found.
                        <br/><a href="#forum" onclick="toggleForumDiv('forum');">Back to Forum</a>
                    `;

                }
            
            } else {

                document.getElementById('forumTopic').innerHTML = `
                    <h1>${forumType}</h1>
                    <br/><button class="addForumPostButton" onclick="toggleForm('addForumPostForm')"
                        data-typeId="${forumTypeId}"
                    >Add Post</button>
                    <br/>No forum posts found.
                    <br/><a href="#forum" onclick="toggleForumDiv('forum');">Back to Forum</a>
                `;
            
            }

            getAddForumPostButtons();

        }

    };

    xhr.onerror = function () {

        document.getElementById('forumTopic').textContent = 'There is an error fetching the data.';
    
    };

}

function showForumPost(postId, userId) {

    toggleForumDiv('forumPost');
    let xhr = new XMLHttpRequest();
    xhr.open('GET', `entities/forum/forum-controller.php?action=getForumById&id=${postId}`, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem('jwt_token'));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();

    xhr.onreadystatechange = function() {
        
        if (xhr.readyState === XMLHttpRequest.DONE) {
            
            if (xhr.status === 200) {
                
                let responseData = JSON.parse(xhr.responseText.trim());

                if(responseData.forumById !== null){

                    let forumTopics = `
                        <h1>${responseData.forumById.title}</h1>
                        ${userId === responseData.forumById.user_id ? `<button class="editForumPostButton" onclick="toggleForm('editForumPostForm')"
                            data-id="${responseData.forumById.forum_id}"
                            data-title="${responseData.forumById.title}"
                            data-body="${responseData.forumById.body}"
                            data-typeId="${responseData.forumById.forum_type_id}"
                        >Edit Post</button>`: ''}
                        ${userId === responseData.forumById.user_id ? `
                            <form class="deleteForumPostForm" method="POST">
                                <input type="hidden" name="forumId" value="${responseData.forumById.forum_id}">
                                <button type="submit" name="deleteForumPostForm">Delete Post</button>
                            </form>
                        `: ''}
                        <br/><p>${decodeHtmlEntities(responseData.forumById.body)}</p>
                        <a href="#forumTopic" onclick="showForumTopic('${responseData.forumById.type}',${responseData.forumById.user_id})">Back to Forum Topic</a>
                    `;
                    document.getElementById('forumPost').innerHTML = forumTopics;
                    getEditForumPostButtons();
                
                }else{

                    document.getElementById('forumPost').textContent = 'No post found.';

                }
            
            } else {

                document.getElementById('forumPost').textContent = 'No post found.';
            
            }

        }

    };

    xhr.onerror = function () {

        document.getElementById('forumPost').textContent = 'There is an error fetching the data.';
    
    };

}

function getEditForumPostButtons(){

    const editForumTypeButtons = document.querySelectorAll(".editForumPostButton");

    editForumTypeButtons.forEach(button => {

        button.addEventListener("click", function() {

            document.getElementById("forumPostId").value = this.getAttribute("data-id");
            document.getElementById("forumPostTitle").value = this.getAttribute("data-title");
            document.getElementById("forumPostTypeId").value = this.getAttribute("data-typeId");
            
            const forumPostBody = ckEditorInstances['forumPostBody'];
            
            if (forumPostBody) {

                forumPostBody.setData(this.getAttribute("data-body"));

            }

        });

    });
    
}

function getAddForumPostButtons(){

    const addForumPostButtons = document.querySelectorAll(".addForumPostButton");

    addForumPostButtons.forEach(button => {

        button.addEventListener("click", function() {

            document.getElementById("addForumTypeId").value = this.getAttribute("data-typeId");

        });

    });
    
}

function decodeHtmlEntities(encodedString) {
    let parser = new DOMParser();
    let decodedString = parser.parseFromString(`<!doctype html><body>${encodedString}`, 'text/html').body.textContent;
    return decodedString;
}