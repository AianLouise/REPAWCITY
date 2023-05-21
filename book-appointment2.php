<?php
session_start(); // Add this line to start the session
require './function/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/book-appointment.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="navbar1" id="myNavbar">
        <a href="index.php" class="logo"><img src="image/logo (1).png" class="img-logo"></a>
        <h1>Make an Appointment</h1>
    </div>
    <div class="progress1">
        <img src="image/book-appointment/progressbar2.png" alt="" class="progressbar">
    </div>
    <div class="content">
        <h2>APPOINTMENT TYPE</h2>
        <select name="action" id="action" style="width: 20rem; height: 3rem;" class="type">
            <option value="">Select</option>
            <option value="adopt">Adopt</option>
            <option value="donate">Donate</option>
            <option value="visit">Visit</option>
            <option value="volunteer">Volunteer</option>
        </select>
        <div class="row">
            <div class="col">
                <a href="book-appointment.php" class="btnn back">Back</a>
            </div>
            <div class="col">
                <a href="book-appointment3.php" class="btnn next">Next</a>
            </div>
        </div>

    </div>
</body>

</html>