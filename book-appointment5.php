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
    <link rel="stylesheet" href="css/book-appointment5.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        .content h1 {
            color: white;
        }

        .container {
            font-size: 1.3em;
            width: 35rem;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        .form-check-label {
            font-weight: bold;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .form-check-input[type="checkbox"] {
            width: 20px;
            height: 20px;
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
            <img src="image/book-appointment/progressbar5.png" alt="" class="progressbar">
        </div>

        <div class="content">
            <h1>Appointment Confirmation</h1>

            <div class="container">
                <form action="home.php" method="POST">
                    <div class="form-group">
                        <label>Please tick the checkboxes to confirm your availability and understanding of the
                            appointment details:</label>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="availability" name="availability"
                                required>
                            <label class="form-check-label" for="availability">I confirm my availability for the
                                scheduled appointment on [date] at [time].</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="location" name="location" required>
                            <label class="form-check-label" for="location">I am aware of the location/address where the
                                appointment will take place.</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="changes" name="changes" required>
                            <label class="form-check-label" for="changes">I will notify you promptly if there are any
                                changes or if I need to reschedule the appointment.</label>
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="btnn ">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</body>

</html>