<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="css/book-appointment3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Acme">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
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
                <div id="calendar"></div>
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
                        <input type="date" class="form-control" name="date" id="date-input" style="width: 20rem;"
                            required>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                selectable: true,
                select: function(start, end) {
                    var selectedDate = moment(start).format('YYYY-MM-DD');
                    $('#date-input').val(selectedDate).change(); // Update the input value and trigger change event
                },
                events: [
                    // Add your events here
                    {
                        title: 'Appointment',
                        start: '2023-05-20T10:00:00',
                        end: '2023-05-20T11:00:00',
                        color: '#378006'
                    },
                    {
                        title: 'Appointment',
                        start: '2023-05-21T14:00:00',
                        end: '2023-05-21T15:00:00',
                        color: '#378006'
                    },
                    // Add more events as needed
                ]
            });
        });
    </script>
</body>

</html>