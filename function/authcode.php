<?php
session_start();
include('config.php');

if (isset($_POST["register"])) {
    $fname = mysqli_real_escape_string($conn, $_POST["fname"]);
    $lname = mysqli_real_escape_string($conn, $_POST["lname"]);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $cpassword = mysqli_real_escape_string($conn, $_POST["cpassword"]);

    $check_email_query = "SELECT email FROM user WHERE email='$email'";
    $check_email_query_run = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        echo '<script language="javascript">';
        echo 'alert("Email already registered");';
        echo 'window.location = "../signuppage.php";';
        echo '</script>';
    } else {

        if ($password == $cpassword) {
            //insert user data
            $insert_query = "INSERT INTO user (fname,lname,username,email,password) VALUES('$fname' , '$lname' , '$username' , '$email' , '$password')";
            $insert_query_run = mysqli_query($conn, $insert_query);

            if ($insert_query) {
                echo '<script language="javascript">';
                echo 'alert("Registered Successfully");';
                echo 'window.location = "../loginpage.php";';
                echo '</script>';
            } else {
                echo '<script language="javascript">';
                echo 'alert("Registered Successfully");';
                echo 'window.location = "../signuppage.php";';
                echo '</script>';
            }
        } else {
            echo '<script language="javascript">';
            echo 'alert("Password do not match");';
            echo 'window.location = "../signuppage.php";';
            echo '</script>';
        }
    }
} elseif (isset($_POST["login"])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $login_query = "SELECT * FROM user WHERE email='$email' AND password='$password'";
    $login_query_run = mysqli_query($conn, $login_query);

    if (mysqli_num_rows($login_query_run) > 0) {
        $_SESSION['auth'] = true;

        $userdata = mysqli_fetch_array($login_query_run);
        $uname = $userdata['username'];
        $useremail = $userdata['email'];

        $_SESSION['auth_user'] = [
            'name' => $uname,
            'email' => $useremail
        ];

        echo '<script language="javascript">';
        echo 'alert("Logged In Successfully");';
        echo 'window.location = "../adminpage.php";';
        echo '</script>';
    } else {
        echo '<script language="javascript">';
        echo 'alert("Invalid Credentials");';
        echo '</script>';
        header('location: ../loginpage.php');

    }
}
?>