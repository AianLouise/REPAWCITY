<?php
session_start();
require '../includes/config.php';

if (isset($_POST['update'])) {
    $oldpassword = $_POST['oldpassword'];
    $newpassword = $_POST['newpassword'];

    // Retrieve the user's old password from the database using a prepared statement
    $user_id = $_SESSION['auth_user']['id'];
    $select_query = "SELECT password FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($select_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $old_password_db = $row['password'];

    // Verify old password (supports both legacy plain-text and hashed)
    $password_matches = password_verify($oldpassword, $old_password_db) || $oldpassword === $old_password_db;

    if ($password_matches) {
        // Hash the new password securely
        $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);

        // Update the user's password in the database using a prepared statement
        $update_query = "UPDATE user SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $hashed_password, $user_id);
        $update_query_run = $stmt->execute();

        if ($update_query_run) {
            echo '<script language="javascript">';
            echo 'alert("Password updated successfully");';
            echo 'window.location = "change-password.php";';
            echo '</script>';
        } else {
            echo '<script language="javascript">';
            echo 'alert("Failed to update password");';
            echo 'window.location = "change-password.php";';
            echo '</script>';
        }
    } else {
        echo '<script language="javascript">';
        echo 'alert("Old password does not match");';
        echo 'window.location = "change-password.php";';
        echo '</script>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="image/icon.png" type="image/png">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>rePaw City</title>
    <link rel="stylesheet" href="css/change-password.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap">
    <script src="https://kit.fontawesome.com/98b545cfa6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="main">
        <a href="home.php" class="btn back">Back</a>
        <div class="content">

            <div class="container">
                <form class="edit" method="POST">
                    <h1>Change Password</h1>
                    <div class="form-group">
                        <label for="oldpassword">Old Password:</label>
                        <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Enter Old Password" required>
                    </div>
                    <div class="form-group">
                        <label for="newpassword">New Password:</label>
                        <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Enter New Password" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>

            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


        </div>
    </div>

    <script>
        // Retrieve user data from the session
        const userData = <?php echo json_encode($_SESSION['auth_user']); ?>;

        // Populate input fields with user data
        document.getElementById('fname').value = userData.fname;
        document.getElementById('lname').value = userData.lname;
        document.getElementById('email').value = userData.email;
    </script>
</body>

</html>