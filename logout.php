<?php
// Start session
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
require_once 'db_connection.php';
// If logout button is clicked, unset and destroy session and redirect to login page
if (isset($_POST['logout'])) {
    unset($_SESSION['user_id']);
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout Example</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <form method="post" action="">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>
</html>