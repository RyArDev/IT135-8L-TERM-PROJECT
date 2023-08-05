<html>
<head>
  <link rel="stylesheet" type="text/css" href="barangaycorner.css">
</head>

<style>

/* General styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
}

h1 {
    margin: 20px;
    font-size: 40px;
    text-align: center;
}

.profile-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin: 20px;
    text-align: center;
    width: 200px; /* Adjust the width as per your preference */
    height: 250px; /* Adjust the height as per your preference */
}

.profile-card img {
    max-width: 100px;
    border-radius: 30%;
    margin-bottom: 10px;
}

/* Specific styles for each branch */
.punong-barangay {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.legislative-branch {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.executive-branch {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

/* Media query for responsiveness */
@media (max-width: 768px) {
    .profile-card {
        width: 150px; /* Distribute cards in 2 columns with equal width */
        margin: 10px;
    }
}

    
</style>

<body>
    <h1>Barangay Office Corner</h1>
    <div class="punong-barangay">
        <div class="profile-card punong-barangay">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Punong Barangay">
            <h3>Punong Barangay</h3>
            <p>Name: Juan Dela Cruz</p>
        </div>
    </div>
   

    <h1>Legislative Branch</h1>
    <div class="legislative-branch">
        <div class="profile-card legislative-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Legislator 1">
            <h3>Legislator 1</h3>
            <p>Name: Maria Santos</p>
        </div>
        <!-- Repeat similar structure for other legislative members -->
        <div class="profile-card legislative-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Legislator 1">
            <h3>Legislator 1</h3>
            <p>Name: Maria Santos</p>
        </div>
        <div class="profile-card legislative-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Legislator 1">
            <h3>Legislator 1</h3>
            <p>Name: Maria Santos</p>
        </div>
        <div class="profile-card legislative-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Legislator 1">
            <h3>Legislator 1</h3>
            <p>Name: Maria Santos</p>
        </div>
    </div>

    <h1>Executive Branch</h1>
    <div class="executive-branch">
        <div class="profile-card executive-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Executive 1">
            <h3>Executive 1</h3>
            <p>Name: Jose Gonzales</p>
        </div>
        <!-- Repeat similar structure for other executive members -->
        <div class="profile-card executive-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Executive 1">
            <h3>Executive 1</h3>
            <p>Name: Jose Gonzales</p>
        </div>
        <div class="profile-card executive-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Executive 1">
            <h3>Executive 1</h3>
            <p>Name: Jose Gonzales</p>
        </div>
        <div class="profile-card executive-member">
            <img src="assets/images/pages/barangay-corner/brngy.png" alt="Executive 1">
            <h3>Executive 1</h3>
            <p>Name: Jose Gonzales</p>
        </div>
    </div>
</body>
</html>