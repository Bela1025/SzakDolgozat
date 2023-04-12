<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $item_id = $_POST['item_id'];

    $sql = "DELETE FROM inventory WHERE inventory_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $item_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: raktar.php");
            exit;
        } else {
            echo "Hiba történt a termék törlése közben. Kérjük, próbálja újra.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
}
?>
