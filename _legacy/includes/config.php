<?php

$servername = "localhost:3306";
$username = "admin";
$password = "admin123";
$dbname = "repawcity";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

?>