<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="Description of your website">
        <meta name="keywords" content="keyword1, keyword2, keyword3">
        <meta name="author" content="Your Name">
        <title>Your Website Title</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="icon" href="favicon.ico" type="image/ico">
    </head>
    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/admin">Admin Dashboard</a></li>
                    <li><a href="/announcement">Announcement</a></li>
                    <li><a href="/barangay">Barangay</a></li>
                    <li><a href="/contact-us">Contact Us</a></li>
                    <li><a href="/forum">Forum</a></li>
                    <li><a href="/history">History</a></li>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                    <li><a href="/services">Services</a></li>
                    <li><a href="/profile">Profile Page</a></li>
                </ul>
            </nav>
        </header>
        <div class="content"><!-- This is where all sub pages are located -->
            <?php 
                if(isset($page) && file_exists($page)) require($page); 
            ?>
        </div>
        <footer>

        </footer>
    </body>
    <script src="script.js"></script>
</html>