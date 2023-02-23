<?php
// MySQL database connection parameters
$host = 'admin';
$username = 'root';
$password = 'jF/rfVlPIJHSjGPc';
$database = 'szakdoga';

// Create MySQL connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
