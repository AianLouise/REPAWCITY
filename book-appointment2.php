<?php
session_start(); // Add this line to start the session
require './function/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and process the form data
    $type = $_POST['type'];
    // ... process other form fields and store the data as needed

    // Store the collected data in session variables
    $_SESSION['appointment_type'] = $type;
    // ... store other form fields in session variables as needed
    header("Location: book-appointment3.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/book-appointment2.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
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
        <form method="POST">
            <select name="type" id="type" style="width: 20rem; height: 3rem;" class="type" required>
                <option value="">Select</option>
                <option value="adopt">Adopt</option>
                <option value="donate">Donate</option>
                <option value="visit">Visit</option>
                <option value="volunteer">Volunteer</option>
            </select>
            <div class="row">
                <div class="col">
                    <a href="book-appointment.php" class="btnn back" style="text-decoration:none; color: black;">Back</a>
                </div>
                <div class="col">
                    <button type="submit" class="btnn next" >Next</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>
