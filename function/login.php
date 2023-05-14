<?php
$email = $_POST['email'];
$password = $_POST['password'];

// echo "$email";

$conn = new mysqli("localhost:3307", "root", "", "repawcity");

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
} else {
    $stmt = $conn->prepare("select * from user where email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt_result = $stmt->get_result();
    if ($stmt_result->num_rows > 0) {
        $data = $stmt_result->fetch_assoc();
        if ($data['password'] === $password) {
            // echo "<p>Login Successfully<p>";
            echo '<script language="javascript">';
            echo 'alert("Login Successful");';
            echo 'window.location = "../loginpage.php";';
            echo '</script>';
        } else {
            echo '<script language="javascript">';
            echo 'alert("CHECK EMAIL OR PASSWORD");';
            echo 'location = "../loginpage.php"';
            echo '</script>';
        }
    } else {
        echo "Invalid Email or Password";
    }

}

?>