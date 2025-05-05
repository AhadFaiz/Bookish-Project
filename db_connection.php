<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "bookish";



$conn = new mysqli($host, $username, $password, $database); // ADD $port here

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
