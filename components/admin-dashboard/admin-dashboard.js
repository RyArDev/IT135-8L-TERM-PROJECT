function getActiveUsers() {

    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'entities/user/user-controller.php?action=getActiveUsers', true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem('jwt_token'));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();

    xhr.onreadystatechange = function() {
        
        if (xhr.readyState === XMLHttpRequest.DONE) {
            
            if (xhr.status === 200) {
                
                let responseData = JSON.parse(xhr.responseText.trim());
                let activeUsersList = responseData.activeUsers.map(user => {
                    return `
                        <div class="active-user-card">
                            <img src="${user.profile_image_url}" alt="Profile Image" id="profile_image_${user.user_id}">
                            <p><strong>Username:</strong> <span id="name_${user.user_id}">${user.username}</span></p>
                            <p><strong>Name:</strong> <span id="name_${user.user_id}">${user.first_name} ${user.last_name}</span></p>
                            <p><strong>Email:</strong> <span id="email_${user.user_id}">${user.email}</span></p>
                        </div>
                    `;
                }).join('');
                document.getElementById('activeUsersList').innerHTML = activeUsersList;
                getEditUserButtons();
            
            } else {

                document.getElementById('activeUsersList').textContent = 'No active users found.';
            
            }

            setTimeout(getActiveUsers, 10 * 1000);

        }

    };

    xhr.onerror = function () {

        document.getElementById('activeUsersList').textContent = 'There is an error fetching the data.';
    
    };

}

function getFilteredUsers(searchValue) {

    if (!searchValue.trim()) {

        document.getElementById('usersList').innerHTML = '';
        document.getElementById('usersList').textContent = 'User/s not found.';
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open('GET', `entities/user/user-controller.php?action=getUsersBySearch&searchValue=${searchValue}`, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem('jwt_token'));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();

    xhr.onreadystatechange = function() {
        
        if (xhr.readyState === XMLHttpRequest.DONE) {
            
            if (xhr.status === 200) {
                
                let responseData = JSON.parse(xhr.responseText.trim());

                if(responseData.searchedUser !== null){

                    const filteredUsers = responseData.searchedUser.filter(user => {
                        return (
                            String(user.user_id).includes(searchValue) ||
                            user.username.toLowerCase().includes(searchValue.toLowerCase()) ||
                            user.first_name.toLowerCase().includes(searchValue.toLowerCase()) ||
                            user.last_name.toLowerCase().includes(searchValue.toLowerCase()) ||
                            user.email.toLowerCase().includes(searchValue.toLowerCase())
                        );
                    });

                    let users = filteredUsers.map(user => {

                        const addressParts = user.address !== null && user.address !== undefined ? user.address.split(', ', 5) : [];

                        return `
                            <div class="user-card">
                                <img src="${user.profile_image_url}" alt="Profile Image" id="profile_image_${user.user_id}">
                                <p><strong>Username:</strong> <span id="name_${user.user_id}">${user.username}</span></p>
                                <p><strong>Name:</strong> <span id="name_${user.user_id}">${user.first_name} ${user.last_name}</span></p>
                                <p><strong>Email:</strong> <span id="email_${user.user_id}">${user.email}</span></p>
                                <button class="editUserButton" onclick="toggleForm('editUserForm')"
                                    data-id="${user.user_id}"
                                    data-profileId=${user.user_profile_id}
                                    data-userDescription="${user.description}"
                                    data-username="${user.username}"
                                    data-firstName="${user.first_name}"
                                    data-lastName="${user.last_name}"
                                    data-email="${user.email}"
                                    data-birthDate="${user.birth_date}"
                                    data-address1="${addressParts.length >= 1 ? addressParts[0] : null}"
                                    data-address2="${addressParts.length >= 2 ? addressParts[1] : null}"
                                    data-city="${addressParts.length >= 3 ? addressParts[2] : null}"
                                    data-stateProvince="${addressParts.length >= 4 ? addressParts[3] : null}"
                                    data-zipCode="${addressParts.length >= 5 ? addressParts[4] : null}"
                                    data-gender="${user.gender}"
                                    data-phoneNumber="${user.phone_number}"
                                    data-jobTitle="${user.job_title}"
                                    data-jobDescription="${user.job_description}"
                                >Edit Profile</button>
                                <button class="changePasswordButton" onclick="toggleForm('changePasswordForm')"
                                    data-id="${user.user_id}"
                                >Edit Password</button>
                            </div>
                        `;
                    }).join('');
                    document.getElementById('usersList').innerHTML = users;
                    getEditUserButtons();

                }else{

                    document.getElementById('usersList').textContent = 'User/s not found.';

                }
                
            
            } else {

                document.getElementById('usersList').textContent = 'User/s not found.';
            
            }

        }

    };

    xhr.onerror = function () {

        document.getElementById('usersList').textContent = 'There is an error fetching the data.';
    
    };

}

function getFilteredAnnouncements(searchValue) {

    let responseData = null;

    if (!searchValue.trim()) {

        document.getElementById('announcementsList').innerHTML = '';
        document.getElementById('announcementsList').textContent = 'Announcement/s not found.';
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open('GET', `entities/announcement/announcement-controller.php?action=getAnnouncementsBySearch&searchValue=${searchValue}`, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem('jwt_token'));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();

    xhr.onreadystatechange = function() {
        
        if (xhr.readyState === XMLHttpRequest.DONE) {
            
            if (xhr.status === 200) {
                
                responseData = JSON.parse(xhr.responseText.trim());

                if(responseData.searchedAnnouncements !== null){

                    const announcementTypeMap = {
                        1: 'barangay',
                        2: 'city',
                        3: 'region',
                        4: 'nation',
                        5: 'emergency',
                        6: 'holiday',
                    };
    
                    const filteredAnnouncements = responseData.searchedAnnouncements.filter(announcement => {
                        
                        let announcement_type = announcement.announcement_type_id;
                        let announcementTypeWord = announcementTypeMap[announcement_type] || '';
    
                        return (
                            String(announcement.announcement_id).includes(searchValue) ||
                            String(announcement.announcement_type_id).includes(searchValue) ||
                            announcement.title.toLowerCase().includes(searchValue.toLowerCase()) ||
                            announcementTypeWord.toLowerCase().includes(searchValue.toLowerCase())
                        );
                    });

                    let announcementsTable = `
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Body</th>
                                <th>Date Created</th>
                                <th>Date Modified</th>
                                <th>User ID</th>
                                <th>Type ID</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            ${filteredAnnouncements.map(announcement => `
                                <tr>
                                    <td>${announcement.announcement_id}</td>
                                    <td>${announcement.title}</td>
                                    <td>${decodeHTMLEntities(announcement.body)}</td>
                                    <td>${announcement.date_created}</td>
                                    <td>${announcement.date_modified}</td>
                                    <td>${announcement.user_id}</td>
                                    <td>${announcement.announcement_type_id}</td>
                                    <td>
                                        <button class="editAnnouncementButton" onclick="toggleForm('editAnnouncementForm')"
                                            data-id="${announcement.announcement_id}"
                                            data-title="${announcement.title}"
                                            data-body="${announcement.body}"
                                            data-type="${announcement.announcement_type_id}"
                                        >
                                            Edit Announcement
                                        </button>
                                    </td>
                                    <td>
                                        <form class="deleteAnnouncementForm" method="POST">
                                            <input type="hidden" name="announcementId" value="${announcement.announcement_id}">
                                            <button type="submit" name="deleteAnnouncementForm">Delete Announcement</button>
                                        </form>
                                    </td>
                                </tr>
                            `).join('')}
                        </table>
                    `;

                    document.getElementById('announcementsList').innerHTML = announcementsTable;
                    getEditAnnouncementButtons();

                }else{

                    document.getElementById('announcementsList').textContent = 'Announcement/s not found.';

                }
            
            } else {

                document.getElementById('announcementsList').textContent = 'Announcement/s not found.';
            
            }

        }

    };

    xhr.onerror = function () {

        document.getElementById('announcementsList').textContent = 'There is an error fetching the data.';
    
    };


}

function getFilteredLogs(searchValue) {

    let responseData = null;

    if (!searchValue.trim()) {

        document.getElementById('logsList').innerHTML = '';
        document.getElementById('logsList').textContent = 'Log/s not found.';
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open('GET', `entities/log/log-controller.php?action=getLogsBySearch&searchValue=${searchValue}`, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + sessionStorage.getItem('jwt_token'));
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send();

    xhr.onreadystatechange = function() {
        
        if (xhr.readyState === XMLHttpRequest.DONE) {
            
            if (xhr.status === 200) {
                
                responseData = JSON.parse(xhr.responseText.trim());

                if(responseData.logs !== null){
    
                    const filteredLogs = responseData.logs.filter(log => {
                        return (
                            String(log.log_id).includes(searchValue) ||
                            String(log.user_id).includes(searchValue) ||
                            log.table_name.toLowerCase().includes(searchValue.toLowerCase()) ||
                            log.description.toLowerCase().includes(searchValue.toLowerCase()) ||
                            log.date_created.toLowerCase().includes(searchValue.toLowerCase())
                        );
                    });

                    let logsTable = `
                        <table>
                            <tr>
                                <th>Log ID</th>
                                <th>Table Name</th>
                                <th>Description</th>
                                <th>Date Created</th>
                                <th>User ID</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                            ${filteredLogs.map(log => `
                                <tr>
                                    <td>${log.log_id}</td>
                                    <td>${log.table_name}</td>
                                    <td>${JSON.stringify(log.description)}</td>
                                    <td>${log.date_created}</td>
                                    <td>${log.user_id}</td>
                                    <td>
                                        <button class="editLogButton" onclick="toggleForm('editLogForm')"
                                            data-id="${log.log_id}"
                                            data-tableName="${log.table_name}"
                                            data-description="${JSON.stringify(log.description)}"
                                            data-dateCreated="${log.date_created}"
                                            data-userId="${log.user_id}"
                                            disabled
                                        >
                                            Edit Log
                                        </button>
                                    </td>
                                    <td>
                                        <form class="deleteLogForm" method="POST">
                                            <input type="hidden" name="logId" value="${log.log_id}">
                                            <button type="submit" name="deleteLogForm" disabled>Delete Log</button>
                                        </form>
                                    </td>
                                </tr>
                            `).join('')}
                        </table>
                    `;

                    document.getElementById('logsList').innerHTML = logsTable;
                    //getEditLogButtons();

                }else{

                    document.getElementById('logsList').textContent = 'Log/s not found.';

                }
            
            } else {

                document.getElementById('logsList').textContent = 'Log/s not found.';
            
            }

        }

    };

    xhr.onerror = function () {

        document.getElementById('LogsList').textContent = 'There is an error fetching the data.';
    
    };


}

function decodeHTMLEntities(text) {

    let txt = document.createElement("textarea");
    txt.innerHTML = text;
    return txt.value;

}

function getEditUserButtons(){

    const editUserButtons = document.querySelectorAll(".editUserButton");
    const changePasswordButtons = document.querySelectorAll(".changePasswordButton");

    editUserButtons.forEach(button => {

        button.addEventListener("click", function() {

            document.getElementById("userId").value = this.getAttribute("data-id");
            document.getElementById("profileId").value = this.getAttribute("data-profileId");
            document.getElementById("userDescription").value = this.getAttribute("data-userDescription");
            document.getElementById("username").value = this.getAttribute("data-username");
            document.getElementById("firstName").value = this.getAttribute("data-firstName");
            document.getElementById("lastName").value = this.getAttribute("data-lastName");
            document.getElementById("email").value = this.getAttribute("data-email");
            document.getElementById("birthDate").value = this.getAttribute("data-birthDate");
            document.getElementById("address1").value = this.getAttribute("data-address1");
            document.getElementById("address2").value = this.getAttribute("data-address2");
            document.getElementById("city").value = this.getAttribute("data-city");
            document.getElementById("stateProvince").value = this.getAttribute("data-stateProvince");
            document.getElementById("zipCode").value = this.getAttribute("data-zipCode");
            document.getElementById("gender").value = this.getAttribute("data-gender");
            document.getElementById("phoneNumber").value = this.getAttribute("data-phoneNumber");
            document.getElementById("jobTitle").value = this.getAttribute("data-jobTitle");
            document.getElementById("jobDescription").value = this.getAttribute("data-jobDescription");

            const userDescription = ckEditorInstances['userDescription'];
            const jobDescription = ckEditorInstances['jobDescription'];
            
            if (userDescription) {

                userDescription.setData(this.getAttribute("data-userDescription"));

            }

            if (jobDescription) {

                jobDescription.setData(this.getAttribute("data-jobDescription"));

            }

        });

    });

    changePasswordButtons.forEach(button => {

        button.addEventListener("click", function() {

            document.getElementById("userPasswordId").value = this.getAttribute("data-id");

        });

    });
    
}

function getEditLogButtons(){

    const editUserButtons = document.querySelectorAll(".editLogButton");

    editUserButtons.forEach(button => {

        button.addEventListener("click", function() {

            document.getElementById("logId").value = this.getAttribute("data-id");
            document.getElementById("logTableName").value = this.getAttribute("data-tableName");
            document.getElementById("logUserId").value = this.getAttribute("data-userId");

            const logDescription = ckEditorInstances['logDescription'];
            
            if (logDescription) {

                logDescription.setData(this.getAttribute("data-description"));

            }

        });

    });
    
}

function initializeAdminReports(){

    getActiveUsers();

    const searchUsersInput = document.getElementById('searchUserInput');
    searchUsersInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') getFilteredUsers(this.value);
    });

    const searchAnnouncementsInput = document.getElementById('searchAnnouncementInput');
    searchAnnouncementsInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') getFilteredAnnouncements(this.value);
    });

    const searchLogsInput = document.getElementById('searchLogsInput');
    searchLogsInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') getFilteredLogs(this.value);
    });

}

initializeAdminReports();

/*

    Logs (Display in Admin Dashboard)

    Forums
    Tickets (Contact-Us) (Display in Admin Dashboard)

    Active User Count (Daily, Weekly, Monthly) (Display in Admin Dashboard)
    Announcement Posts Count (Daily, Weekly, Monthly) (Display in Admin Dashboard)
    Forum Posts Count (Daily, Weekly, Monthly) (Display in Admin Dashboard)
    Unique Site Visitors (Display in Admin Dashboard)

    Services Page and Homepage
    ONLINE BARANGAY ID SERVED
    ONLINE BUSINESS PERMIT SERVED
    NEW CLEARANCES

    Comments

*/