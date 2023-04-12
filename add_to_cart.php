<?php
session_start();

// Adatbázis kapcsolódás
require_once 'db_connection.php';
// Ellenőrizzük, hogy a kosár nem üres-e
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    // Végig iterálunk az összes kosár elemen és hozzáadjuk az adatbázishoz
    foreach ($_SESSION['cart'] as $item) {
        $inventory_id = $item['inventory_id'];
        $item_quantity = $item['quantity'];

        // Ellenőrizzük, hogy az adott elem már szerepel-e az adatbázisban
        $sql = "SELECT * FROM order_items WHERE order_id = ? AND inventory_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $_SESSION['order_id'], $inventory_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Ha az elem már szerepel az adatbázisban, akkor frissítjük a mennyiségét
            $sql = "UPDATE order_items SET item_quantity = item_quantity + ? WHERE order_id = ? AND inventory_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iii", $item_quantity, $_SESSION['order_id'], $inventory_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        } else {
            // Ha az elem még nem szerepel az adatbázisban, akkor hozzáadjuk az adatbázishoz
            $sql = "INSERT INTO order_items (order_id, inventory_id, item_quantity) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "iii", $_SESSION['order_id'], $inventory_id, $item_quantity);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // A kosár ürítése
    unset($_SESSION['cart']);

    // Átirányítás a kosár oldalra
    header("Location: cart.php");
    exit();
} else {
    // Ha a kosár üres, akkor hibaüzenetet jelenítünk meg
    echo "A kosár üres!";
}
?>
