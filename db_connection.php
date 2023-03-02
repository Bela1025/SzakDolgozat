<?php
// MySQL database connection parameters
$host = 'localhost';
$username = 'c31vargabeladbu';
$password = 'gvzuhH4AE#7R';
$database = 'c31vargabeladb';

// Create MySQL connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
