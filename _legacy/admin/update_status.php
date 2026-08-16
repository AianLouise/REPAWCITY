<?php
require '../includes/admin_guard.php';

// Check if the appointment ID and status values are provided
if (isset($_POST['appointmentId']) && isset($_POST['status'])) {
    $appointmentId = $_POST['appointmentId'];
    $status = $_POST['status'];

    // Validate status values
    if (!in_array($status, ['Accepted', 'Cancelled'])) {
        http_response_code(400);
        echo "Invalid status value";
        exit;
    }

    $message = '';
    if ($status == 'Accepted') {
        $message = "Good Day, Ma'am/Sir,\n\nYour appointment is confirmed. Kindly message us within 24 hours if you would like to reschedule or cancel your appointment. Thank you!\n\nVery truly yours,\nRePaw City";
    } elseif ($status == 'Cancelled') {
        $message = "Good Day, Ma'am/Sir,\n\nWe're sincerely sorry to cancel your appointment because of the sudden circumstances in our shelter. We hope for your consideration. Thank you.\n\nVery truly yours,\nRePaw City";
    }

    // Update the status and message in the database using a prepared statement
    $query = "UPDATE appointment SET status = ?, message = ? WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $status, $message, $appointmentId);
    $result = $stmt->execute();

    // Check if the update was successful
    if ($result) {
        // Return a success response
        http_response_code(200);
        echo "Status updated successfully";
    } else {
        // Return an error response
        http_response_code(500);
        echo "Error updating status: " . mysqli_error($conn);
    }

    $stmt->close();
    // Close the database connection
    mysqli_close($conn);
} else {
    // Return an error response if the appointment ID and status values are not provided
    http_response_code(400);
    echo "Invalid request";
}
?>
