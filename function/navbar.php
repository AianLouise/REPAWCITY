
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
</head>

<body>

    <div class="navbar" id="myNavbar">
        <a href="index.php" class="logo"><img src="image/logo (1).png" class="img-logo"></a>
        <a href="home.php" class="list a n1">Home</a>
        <a href="adoptpage.php" class="list n2">Adopt</a>
        <a href="donatepage.php" class="list n3">Donate</a>
        <a href="news.php" class="list n4">News</a>
        <a href="volunteer.php" class="list n5">Volunteer</a>
        <div class="dropdown dr">
            <a href="javascript:void(0);" class="list n6">About Us &#9660;</a>
            <div class="dropdown-content">
                <a href="success-stories.php" class="a1">Success Stories</a>
                <a href="FAQ.php" class="a2">FAQ</a>
                <a href="contact.php" class="a3">Contact</a>
                <a href="team.php" class="a4">Team</a>
            </div>
        </div>
        <?php
        // Check if user is logged in
        if (isset($_SESSION['auth']) && $_SESSION['auth']) {
            // Display profile dropdown and logout button
            ?>
            <div class="dropdown dd-p">
                <a href="javascript:void(0);" class="list profile">Profile &#9660;</a>
                <div class="dropdown-content">
                    <a href="edit-profile.php">Edit Profile</a>
                    <a href="change-password.php">Change Password</a>
                </div>
            </div>
            <a href="javascript:void(0);" class="list logout" onclick="logout()">Logout</a>
            <?php
        } else {
            // Display login and sign up buttons
            ?>
            <a href="loginpage.php" class="list login">Log In</a>
            <a href="signuppage.php" class="list signup">Sign Up</a>
            <?php
        }
        ?>
        <a href="javascript:void(0);" class="icon" onclick="toggleMenu()">&#9776;</a>
    </div>

    <script>
        function toggleMenu() {
            var navbar = document.getElementById("myNavbar");
            if (navbar.className === "navbar") {
                navbar.className += " responsive";
            } else {
                navbar.className = "navbar";
            }
        }

        function logout() {
            if (confirm("Are you sure you want to log out?")) {
                // Perform logout action
                window.location.href = "logout.php";
            }
        }
    </script>
</body>

</html>