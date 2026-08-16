<?php
require '../includes/admin_guard.php';

$counts = [
    'total' => "SELECT COUNT(*) FROM appointment",
    'adopt' => "SELECT COUNT(*) FROM appointment WHERE appointment_type = 'Adopt'",
    'donate' => "SELECT COUNT(*) FROM appointment WHERE appointment_type = 'Donate'",
    'visit' => "SELECT COUNT(*) FROM appointment WHERE appointment_type = 'Visit'",
    'volunteer' => "SELECT COUNT(*) FROM appointment WHERE appointment_type = 'Volunteer'",
];

foreach ($counts as $key => $query) {
    $counts[$key] = (int) mysqli_fetch_row(mysqli_query($conn, $query))[0];
}
extract($counts);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard — rePaw City Admin</title>
    <?php require '../includes/admin_head.php'; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
    <style>
        #calendar {
            background: #fff;
            border-radius: 1rem;
            padding: 0.5rem;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev',
                    center: 'title',
                    right: 'next'
                },
                selectable: true,
                select: function (start, end) {
                    var selectedDate = moment(start).format('YYYY-MM-DD');
                    var today = moment().startOf('day');

                    $('#date-input').val(selectedDate).change();
                },
                events: [
                    <?php
                    $query = "SELECT appointment_date, time_slot FROM appointment";
                    $result = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        $date = $row['appointment_date'];
                        $time_slot = $row['time_slot'];

                        $event = [
                            "title" => "$time_slot",
                            "start" => $date,
                            "end" => $date,
                            "color" => "#378006"
                        ];
                        echo json_encode($event) . ",";
                    }
                    ?>
                ],
                eventRender: function (event, element) {
                    if (event.title === 'Morning Session' && hasAfternoonSession(event.start)) {
                        element.css('background-color', '#fad046');
                        element.css('border-color', '#fad046');
                        element.addClass('unselectable');
                    } else if (event.title === 'Afternoon Session' && hasMorningSession(event.start)) {
                        element.css('background-color', '#fad046');
                        element.css('border-color', '#fad046');
                        element.addClass('unselectable');
                    }
                },

            });

            function hasMorningSession(date) {
                var events = $('#calendar').fullCalendar('clientEvents');
                for (var i = 0; i < events.length; i++) {
                    if (events[i].title === 'Morning Session' && moment(events[i].start).isSame(date, 'day')) {
                        return true;
                    }
                }
                return false;
            }

            function hasAfternoonSession(date) {
                var events = $('#calendar').fullCalendar('clientEvents');
                for (var i = 0; i < events.length; i++) {
                    if (events[i].title === 'Afternoon Session' && moment(events[i].start).isSame(date, 'day')) {
                        return true;
                    }
                }
                return false;
            }
        });

    </script>
</head>

<body class="font-sans bg-repaw-bg text-repaw-text antialiased">
    <?php require '../includes/admin_navbar.php'; ?>

    <div class="flex min-h-[calc(100vh-4rem)]">
        <?php require '../includes/admin_sidebar.php'; ?>

        <main class="flex-1 p-6 sm:p-10 space-y-8">
            <!-- Stat cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-4">
                    <span class="mui-icon text-4xl text-repaw-dark">event_available</span>
                    <div>
                        <div class="font-serif text-3xl font-bold text-repaw-dark"><?php echo $total; ?></div>
                        <div class="text-sm text-repaw-text/80">Total Appointments</div>
                    </div>
                </div>
                <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-4">
                    <span class="mui-icon text-4xl text-repaw-dark">pets</span>
                    <div>
                        <div class="font-serif text-3xl font-bold text-repaw-dark"><?php echo $adopt; ?></div>
                        <div class="text-sm text-repaw-text/80">Adopt</div>
                    </div>
                </div>
                <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-4">
                    <span class="mui-icon text-4xl text-repaw-dark">volunteer_activism</span>
                    <div>
                        <div class="font-serif text-3xl font-bold text-repaw-dark"><?php echo $donate; ?></div>
                        <div class="text-sm text-repaw-text/80">Donate</div>
                    </div>
                </div>
                <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-4">
                    <span class="mui-icon text-4xl text-repaw-dark">visibility</span>
                    <div>
                        <div class="font-serif text-3xl font-bold text-repaw-dark"><?php echo $visit; ?></div>
                        <div class="text-sm text-repaw-text/80">Visit</div>
                    </div>
                </div>
                <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm flex items-center gap-4">
                    <span class="mui-icon text-4xl text-repaw-dark">groups</span>
                    <div>
                        <div class="font-serif text-3xl font-bold text-repaw-dark"><?php echo $volunteer; ?></div>
                        <div class="text-sm text-repaw-text/80">Volunteer</div>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
                <div id="calendar"></div>
            </div>

            <form action="" method="post" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="date-input" class="block text-sm font-medium text-repaw-dark mb-1.5">Select Date:</label>
                    <input type="date" class="w-full rounded-xl border border-repaw-hover bg-repaw-bg px-4 py-2.5 text-repaw-text focus:outline-none focus:ring-2 focus:ring-repaw-text" name="date-input" id="date-input" required onchange="submitForm()">
                </div>
            </form>

            <script>
                function submitForm() {
                    document.getElementById('date-input').closest('form').submit();
                }
            </script>

            <?php
            if (isset($_POST['date-input'])) {
                $date = $_POST['date-input'];
            } else {
                $date = date("Y-m-d");
            }

            $dateInWords = date("F j, Y", strtotime($date));

            echo '<div class="text-center">';
            echo '<div class="font-serif text-2xl font-bold text-repaw-dark">' . $dateInWords . '</div>';
            echo '</div>';
            ?>

            <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
                <div class="font-serif text-xl font-semibold text-repaw-dark mb-4">Morning Session</div>
                <div class="overflow-x-auto rounded-2xl border border-repaw-hover/40">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-repaw-bg/70 text-repaw-dark">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Type</th>
                                <th class="px-4 py-3 font-semibold">Name</th>
                                <th class="px-4 py-3 font-semibold">Mobile #</th>
                                <th class="px-4 py-3 font-semibold">Address</th>
                                <th class="px-4 py-3 font-semibold">Email</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-repaw-hover/40">
                            <?php
                            if (isset($_POST['date-input'])) {
                                $date = $_POST['date-input'];
                                $time_slot = 'Morning Session';
                                $query = "SELECT * FROM appointment WHERE appointment_date = '$date' AND time_slot = '$time_slot'";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $type = $row['appointment_type'];
                                        $firstName = $row['first_name'];
                                        $middleName = $row['middle_name'];
                                        $lastName = $row['last_name'];
                                        $mobile = $row['mobile_number'];
                                        $address = $row['home_address'];
                                        $email = $row['email_address'];
                                        $status = $row['status'];
                                        $appointmentId = $row['appointment_id'];
                                        $fullName = $firstName . ' ' . $middleName . ' ' . $lastName;

                                        echo '<tr>';
                                        echo '<td class="px-4 py-3">' . $type . '</td>';
                                        echo '<td class="px-4 py-3 font-medium text-repaw-dark">' . $fullName . '</td>';
                                        echo '<td class="px-4 py-3">' . $mobile . '</td>';
                                        echo '<td class="px-4 py-3">' . $address . '</td>';
                                        echo '<td class="px-4 py-3">' . $email . '</td>';
                                        echo '<td class="px-4 py-3">';
                                        if ($status == 'Pending') {
                                            echo '<button class="accept-btn inline-flex items-center gap-1 bg-repaw-text text-repaw-bg rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors mr-2" data-appointment-id="' . $appointmentId . '"><span class="mui-icon text-[16px]">check_circle</span>Accept</button>';
                                            echo '<button class="cancel-btn inline-flex items-center gap-1 bg-repaw-danger text-white rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity" data-appointment-id="' . $appointmentId . '"><span class="mui-icon text-[16px]">cancel</span>Cancel</button>';
                                        } else {
                                            echo $status;
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="px-4 py-6 text-center text-repaw-text/70">No appointments available</td></tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white/70 rounded-3xl p-6 border border-repaw-hover/40 shadow-sm">
                <div class="font-serif text-xl font-semibold text-repaw-dark mb-4">Afternoon Session</div>
                <div class="overflow-x-auto rounded-2xl border border-repaw-hover/40">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-repaw-bg/70 text-repaw-dark">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Type</th>
                                <th class="px-4 py-3 font-semibold">Name</th>
                                <th class="px-4 py-3 font-semibold">Mobile #</th>
                                <th class="px-4 py-3 font-semibold">Address</th>
                                <th class="px-4 py-3 font-semibold">Email</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-repaw-hover/40">
                            <?php
                            if (isset($_POST['date-input'])) {
                                $date = $_POST['date-input'];
                                $time_slot = 'Afternoon Session';
                                $query = "SELECT * FROM appointment WHERE appointment_date = '$date' AND time_slot = '$time_slot'";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $type = $row['appointment_type'];
                                        $firstName = $row['first_name'];
                                        $middleName = $row['middle_name'];
                                        $lastName = $row['last_name'];
                                        $mobile = $row['mobile_number'];
                                        $address = $row['home_address'];
                                        $email = $row['email_address'];
                                        $status = $row['status'];
                                        $appointmentId = $row['appointment_id'];

                                        $fullName = $firstName . ' ' . $middleName . ' ' . $lastName;

                                        echo '<tr>';
                                        echo '<td class="px-4 py-3">' . $type . '</td>';
                                        echo '<td class="px-4 py-3 font-medium text-repaw-dark">' . $fullName . '</td>';
                                        echo '<td class="px-4 py-3">' . $mobile . '</td>';
                                        echo '<td class="px-4 py-3">' . $address . '</td>';
                                        echo '<td class="px-4 py-3">' . $email . '</td>';
                                        echo '<td class="px-4 py-3">';
                                        if ($status == 'Pending') {
                                            echo '<button class="accept-btn inline-flex items-center gap-1 bg-repaw-text text-repaw-bg rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wide hover:bg-repaw-dark transition-colors mr-2" data-appointment-id="' . $appointmentId . '"><span class="mui-icon text-[16px]">check_circle</span>Accept</button>';
                                            echo '<button class="cancel-btn inline-flex items-center gap-1 bg-repaw-danger text-white rounded-full px-4 py-1.5 text-xs font-medium uppercase tracking-wide hover:opacity-90 transition-opacity" data-appointment-id="' . $appointmentId . '"><span class="mui-icon text-[16px]">cancel</span>Cancel</button>';
                                        } else {
                                            echo $status;
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="6" class="px-4 py-6 text-center text-repaw-text/70">No appointments available</td></tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const acceptButtons = document.querySelectorAll('.accept-btn');
        acceptButtons.forEach(button => {
            button.addEventListener('click', function () {
                const appointmentId = this.dataset.appointmentId;
                updateStatus(appointmentId, 'Accepted');
            });
        });

        const cancelButtons = document.querySelectorAll('.cancel-btn');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const appointmentId = this.dataset.appointmentId;
                updateStatus(appointmentId, 'Cancelled');
            });
        });

        function updateStatus(appointmentId, status) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_status.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    location.reload();
                }
            };
            xhr.send('appointmentId=' + appointmentId + '&status=' + status);
        }
    </script>
</body>

</html>
