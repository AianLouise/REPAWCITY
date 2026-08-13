<?php require '../includes/user_guard.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="image/icon.png" type="image/png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/notification.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php include '../includes/navbar.php' ?>
    <section class="home">
        <div class="contact-wrapper">
            <div class="contact-wrapper">
                <div class="contact-container">
                    <div class="contact-info">
                        <p>
                            <?php
                            // Fetch the message for a specific appointment
                            if (isset($_GET['appointmentId'])) {
                                $appointmentId = $_GET['appointmentId'];

                                // Query the appointment table to retrieve the message using a prepared statement
                                $stmt = $conn->prepare("SELECT message FROM appointment WHERE appointment_id = ?");
                                $stmt->bind_param("i", $appointmentId);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Check if a message is found
                                if ($result->num_rows > 0) {
                                    // Display the message
                                    $row = $result->fetch_assoc();
                                    $message = $row['message'];
                                    $formattedMessage = nl2br($message); // Convert new lines to HTML line breaks
                                    ?>
                                    <p>
                                        <?php echo $formattedMessage; ?>
                                    </p>
                                    <?php
                                } else {
                                    // No message found for the specified appointment
                                    ?>
                                    <p>No message found for this appointment.</p>
                                    <?php
                                }
                                $stmt->close();
                            } else {
                                // No appointment ID provided
                                ?>
                                <p>No appointment ID provided.</p>
                                <?php
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>


    </section>

    <?php include '../includes/footer.php' ?>

</body>

</html>