<?php

require_once 'db_connection.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // SQL statement to insert message into database
    $sql = "INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    
    // Prepare statement
    $stmt = mysqli_stmt_init($conn);

    // Check if statement is valid
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // Error handling
        die("Statement preparation failed: " . mysqli_error($conn));
    } else {
        // Bind parameters to statement
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
        
        // Execute statement
        mysqli_stmt_execute($stmt);
        
        // Redirect to success page
        header("Location: contact.html");
        exit();
    }
}
