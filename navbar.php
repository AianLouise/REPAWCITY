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
        <a href="index.php" class="logo"><img src="./image/logo (1).png" class="img-logo"></a>
        <a href="home.php" class="list a">Home</a>
        <a href="adoptpage.php" class="list">Adopt</a>
        <a href="donatepage.php" class="list">Donate</a>
        <a href="d" class="list">News</a>
        <a href="navbar.php" class="list">Volunteer</a>
        <a href="homenew.php" class="list">About Us</a>
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
    </script>
</body>

</html>