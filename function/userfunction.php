<?php

session_start();
include('./function/config.php');

function getAllActive($table)
{
    global $conn;
    $query = "SELECT * FROM $table";
    return $query_run = mysqli_query($conn, $query);
}

?>