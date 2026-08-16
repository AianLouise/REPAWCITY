<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

// Retrieve the selected dropdown values
$type = $_GET['type'] ?? '';
$sex = $_GET['sex'] ?? '';
$weight = $_GET['weight'] ?? '';
$age = $_GET['age'] ?? '';

// Build the SQL query with the selected criteria using prepared statements
$query = "SELECT * FROM pets WHERE 1=1";
$params = [];
$types = "";

if (!empty($type)) {
    $query .= " AND type = ?";
    $params[] = $type;
    $types .= "s";
}
if (!empty($sex)) {
    $query .= " AND sex = ?";
    $params[] = $sex;
    $types .= "s";
}
if (!empty($weight)) {
    $query .= " AND weight = ?";
    $params[] = $weight;
    $types .= "s";
}
if (!empty($age)) {
    $query .= " AND age = ?";
    $params[] = $age;
    $types .= "s";
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$pet_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
$stmt->close();

?>
