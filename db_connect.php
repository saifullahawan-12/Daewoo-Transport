<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "daewoo_transport";

$conn = new mysqli("localhost", "root", "", "daewoo_transport");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
