<?php
    //Checks if the user is logged in before.
    include_once('utilities/authentication/auth-controller.php');
    $user = checkUserLogin();
?>

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
        <link rel="icon" href="favicon.ico" type="image/ico">
        <link rel="stylesheet" type="text/css" href="global.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
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
                    <li><a href="/services">Services</a></li>
                    <?php
                        if(isset($_SESSION['user_id'])){

                            echo "<li><a href='/profile'>" . $user['username'] . "</a></li>" .
                            "<li><a href='utilities/authentication/logout.php'>Logout</a></li>";

                        }else{

                            echo "<li><a href='/login'>Login</a></li>" . 
                            "<li><a href='/register'>Register</a></li>";

                        }
                    ?>
                </ul>
            </nav>
        </header>

        <div class="page-content">
            <?php 
                if(isset($page) && file_exists($page)) {

                    require($page); 

                }else{

                    require($errorPage);

                }
            ?>
        </div>

        <footer>

        </footer>
    </body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.min.js" integrity="sha512-/F8YhC3n5OrM9ta9htMD620kH0paKnjDHCHcSvyWumxlqsnkS/XCpYExuMZuXE4K3GE9tDQFBqgXsmkjsjRbDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.sync.min.js" integrity="sha512-WGR8mp0bTMUqHRjk/FmZ6jFraoRFWJGXtEdj5p1PZmGxVWPPb5y4CIj96O8cFpigsE1cuR7Y0w2oTCMuk5nY0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sass.js/0.11.1/sass.worker.min.js" integrity="sha512-HDxgoK5C789rI2GQb7GzGO5uUOWPQPumO816JFI4QLKr83eC+s9eUF9MOuYGqZOdD8v5JMMkrWZ+622UJ4sG4Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <script src="utilities/validation/client/user-status-validation.js"></script>
</html>