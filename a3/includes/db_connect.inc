<?php
$host = "localhost";
$user = "root";   
$pass = "";       
$dbname = "skillswap";  


if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') != false) {
    // Production settings for Jacob 5
    $host = 'talsprddb02.int.its.rmit.edu.au';
    $user = 's4144999';  // Your student ID as username
    $pass = 'Cryofrost25@';  // Replace with actual password from RMIT IT
    $dbname = 's4144999';  // Your student ID as database name
}

// Create connection using MySQLi
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    // Different error handling for development vs production
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        // Development environment - show detailed error
        die("Connection failed: " . mysqli_connect_error());
    } else {
        // Production environment - show generic error
        die("Connection failed. Please try again later.");
    }
}

// Set charset to UTF-8 for proper character handling
mysqli_set_charset($conn, "utf8");

?>

