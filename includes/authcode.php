<!-- Sign Up  -->
<?php
session_start();
include('config.php');

if (isset($_POST["register"])) {
    $fname = mysqli_real_escape_string($conn, $_POST["fname"]);
    $lname = mysqli_real_escape_string($conn, $_POST["lname"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);

    $check_email_query = "SELECT email FROM user WHERE email='$email'";
    $check_email_query_run = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        echo '<script language="javascript">';
        echo 'alert("Email already registered");';
        echo 'window.location = "../auth/signuppage.php";';
        echo '</script>';
    } else {

        if ($password == $cpassword) {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            //insert user data
            $insert_query = "INSERT INTO user (fname, lname, email, password, user_type) VALUES('$fname' , '$lname' , '$email' , '$hashed_password' , 2)";
            $insert_query_run = mysqli_query($conn, $insert_query);

            if ($insert_query) {
                echo '<script language="javascript">';
                echo 'alert("Registered Successfully");';
                echo 'window.location = "../auth/loginpage.php";';
                echo '</script>';
            } else {
                echo '<script language="javascript">';
                echo 'alert("Registered Successfully");';
                echo 'window.location = "../auth/signuppage.php";';
                echo '</script>';
            }
        } else {
            echo '<script language="javascript">';
            echo 'alert("Password do not match");';
            echo 'window.location = "../auth/signuppage.php";';
            echo '</script>';
        }
    }
} elseif (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    // Fetch the user by email using a prepared statement
    $login_query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($login_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userdata = $result->fetch_assoc();

        // Verify the password hash
        if (!password_verify($password, $userdata['password'])) {
            // Legacy migration: check if this is an old plain-text password
            if ($password === $userdata['password']) {
                // Rehash the password and update the DB
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
                $update_stmt->bind_param("si", $new_hash, $userdata['user_id']);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                echo '<script language="javascript">';
                echo 'alert("Invalid credentials");';
                echo 'window.location = "../auth/loginpage.php";';
                echo '</script>';
                exit;
            }
        }

        $userType = $userdata['user_type'];
        $userID = $userdata['user_id']; // Get the ID of the logged-in user

        if ($userType == 1) {
            // Admin user
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = [
                'id' => $userID,
                // Save the ID in the session
                'fname' => $userdata['fname'],
                'lname' => $userdata['lname'],
                'email' => $userdata['email']
            ];

            echo '<script language="javascript">';
            echo 'window.location = "../admin/admin-dashboard.php";';
            echo 'alert("Logged In Successfully as Admin");';
            echo '</script>';
        } elseif ($userType == 2) {
            // Regular user
            $_SESSION['auth'] = true;
            $_SESSION['auth_user'] = [
                'id' => $userID,
                // Save the ID in the session
                'fname' => $userdata['fname'],
                'lname' => $userdata['lname'],
                'email' => $userdata['email']
            ];

            echo '<script language="javascript">';
            echo 'window.location = "../index.php";';
            echo '</script>';
        } else {
            // Invalid user type
            echo '<script language="javascript">';
            echo 'alert("Invalid user type");';
            echo 'window.location = "../auth/loginpage.php";';
            echo '</script>';
        }
    } else {
        // Login failed
        echo '<script language="javascript">';
        echo 'alert("Invalid credentials");';
        echo 'window.location = "../auth/loginpage.php";';
        echo '</script>';
    }
}

?>