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
    <link rel="stylesheet" href="css/book-appointment3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="main">
        <div class="navbar1" id="myNavbar">
            <a href="index.php" class="logo"><img src="image/logo (1).png" class="img-logo"></a>
            <h1>Make an Appointment</h1>
        </div>
        <div class="progress1">
            <img src="image/book-appointment/progressbar3.png" alt="" class="progressbar">
        </div>
        <div class="content">
            <div class="calendar-container">
                <h2 class="title">CHOOSE APPOINTMENT DATE</h2>
                <h2 class="cl">Schedule Calendar</h2>
                <div class="navigation">
                    <a href="#" id="prev-month">&lt; Previous</a>
                    <span id="current-month"></span>
                    <a href="#" id="next-month">Next &gt;</a>
                </div>
                <div class="calendar">
                    <?php
                    // Array of dates to highlight
                    $highlightedDates = [2, 3, 10, 18];
                    ?>
                    <?php
                    $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

                    // Get the current year and month
                    $year = date('Y');
                    $month = date('m');

                    // Get the first day of the month
                    $firstDayOfMonth = date('w', strtotime($year . '-' . $month . '-01'));

                    // Get the number of days in the current month
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

                    // Display the days of the week
                    foreach ($daysOfWeek as $dayOfWeek) {
                        echo '<div class="day">' . $dayOfWeek . '</div>';
                    }

                    // Add empty cells for the days before the first day of the month
                    for ($i = 0; $i < $firstDayOfMonth; $i++) {
                        echo '<div class="day"></div>';
                    }

                    // Display the days of the month
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $date = $year . '-' . $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $isHighlighted = in_array($day, $highlightedDates); // Check if the date should be highlighted

                        $dayClass = 'day' . ($isHighlighted ? ' highlight' : '');
                        echo '<div class="' . $dayClass . '" data-date="' . $date . '">' . $day . '</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="info">
                <div class="available">
                    <div class="square"></div>
                    <h5>Available booking slots.</h5>
                </div>
            </div>
            <div class="info2">
                <div class="unavailable">
                    <div class="square"></div>
                    <h5>Slot on this day has been booked.</h5>
                </div>
            </div>

            <div class="container" style="margin-top: 20px;">
                <form method="POST" action="process_appointment.php">
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" class="form-control" name="date" id="date-input" style="width: 20rem;" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Time:</label>
                        <input type="time" class="form-control" name="time" style="width: 20rem;" required>
                    </div>
                </form>
            </div>

            <div class="button-container">
                <a href="book-appointment2.php" class="btnn  back">Back</a>
                <a href="book-appointment4.php" class="btnn  next">Next</a>
            </div>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var prevMonthLink = document.getElementById('prev-month');
            var nextMonthLink = document.getElementById('next-month');
            var currentMonthSpan = document.getElementById('current-month');
            var dateInput = document.getElementById('date-input');

            prevMonthLink.addEventListener('click', function (e) {
                e.preventDefault();
                changeMonth(-1);
            });

            nextMonthLink.addEventListener('click', function (e) {
                e.preventDefault();
                changeMonth(1);
            });

            function changeMonth(offset) {
                var currentDate = new Date(currentMonthSpan.innerText + ' 1');
                currentDate.setMonth(currentDate.getMonth() + offset);

                var year = currentDate.getFullYear();
                var month = currentDate.getMonth() + 1;

                // Update the current month and year in the UI
                currentMonthSpan.innerText = new Intl.DateTimeFormat('en-US', {
                    year: 'numeric',
                    month: 'long'
                }).format(currentDate);

                // Retrieve the calendar data for the specified month and year using AJAX or any other method
                // and update the calendar UI accordingly
            }

            // Set the initial current month and year
            currentMonthSpan.innerText = new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'long'
            }).format(new Date());

            // Add event listener to the calendar days
            var calendarDays = document.getElementsByClassName('day');
            for (var i = 0; i < calendarDays.length; i++) {
                calendarDays[i].addEventListener('click', function () {
                    var selectedDate = this.getAttribute('data-date');
                    dateInput.value = selectedDate;
                });
            }
        });
    </script>

</body>

</html>
