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
    <link rel="stylesheet" href="css/book-appointment4.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .main {
            height: 75rem;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        form {
            width: 40rem;
            margin-top: -50px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="tel"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            margin-bottom: 10px;
            text-align: center;
        }

        button[type="submit"] {
            background-color: #4caf50;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="navbar1" id="myNavbar">
            <a href="index.php" class="logo"><img src="image/logo (1).png" class="img-logo"></a>
            <h1>Make an Appointment</h1>
        </div>
        <div class="progress1">
            <img src="image/book-appointment/progressbar4.png" alt="" class="progressbar">
        </div>

        <div class="content">
            <h1>Personal Information</h1>
            <form action="process_form.php" method="POST">
                <div>
                    <label for="first-name">First Name:</label>
                    <input type="text" id="first-name" name="first_name" required>
                </div>

                <div>
                    <label for="middle-name">Middle Name:</label>
                    <input type="text" id="middle-name" name="middle_name">
                </div>

                <div>
                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" name="last_name" required>
                </div>

                <div>
                    <label for="mobile-number">Mobile Number:</label>
                    <input type="tel" id="mobile-number" name="mobile_number" required>
                </div>

                <div>
                    <label for="home-address">Home Address:</label>
                    <input type="text" id="home-address" name="home_address" required>
                </div>

                <div>
                    <label for="email-address">Email Address:</label>
                    <input type="email" id="email-address" name="email_address" required>
                </div>
            </form>
            <div class="info">
                <p>We value your privacy. Your information will not be used for purposes other than this appointment
                    application.</p>
            </div>
            <div class="row">
                <div class="col">
                    <a href="book-appointment3.php" class="btnn back">Back</a>
                </div>
                <div class="col">
                    <a href="book-appointment5.php" class="btnn next">Next</a>
                </div>
            </div>

        </div>
    </div>
</body>

</html>