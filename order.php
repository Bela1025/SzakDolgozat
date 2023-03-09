<?php
// Adatbázis kapcsolódás

// Kosár kezelése
session_start();
require_once 'db_connection.php';
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}


// Hozzáadás a kosárhoz
if (isset($_POST['add_to_cart'])) {
    $inventory_id = $_POST['inventory_id'];
    $quantity = $_POST['quantity'];
    $_SESSION['cart'][$inventory_id] = $quantity;
}

// Eltávolítás a kosárból
if (isset($_POST['remove_from_cart'])) {
    $inventory_id = $_POST['inventory_id'];
    unset($_SESSION['cart'][$inventory_id]);
}

// Rendelés leadása
if (isset($_POST['submit_order'])) {
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];
    $customer_phone = $_POST['customer_phone'];
    $order_items = $_SESSION['cart'];

    // Insert a megrendelés az orders táblába
    $sql = "INSERT INTO orders (customer_name, customer_email, customer_phone) VALUES ('$customer_name', '$customer_email', '$customer_phone')";
    if (mysqli_query($conn, $sql)) {
        $order_id = mysqli_insert_id($conn);

        // Insert a rendelt termékek az order_items táblába
        foreach ($order_items as $inventory_id => $quantity) {
            $sql = "INSERT INTO order_items (order_id, inventory_id, item_quantity) VALUES ($order_id, $inventory_id, $quantity)";
            mysqli_query($conn, $sql);

            // Csökkentjük az inventory táblában a termék mennyiségét a rendelt mennyiséggel
            $sql = "UPDATE inventory SET item_quantity = item_quantity - $quantity WHERE inventory_id = $inventory_id";
            mysqli_query($conn, $sql);
        }

        // Töröljük a kosarat
        $_SESSION['cart'] = array();

        // Visszairányítjuk a felhasználót a köszönő oldalra
        header('Location: thank_you.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Lekérdezzük az inventory táblát
$sql = "SELECT inventory.*, locations.location_name FROM inventory LEFT JOIN locations ON inventory.location_id = locations.location_id";
$result = mysqli_query($conn, $sql);
if (isset($_POST['submit'])) {
    // Adatok ellenőrzése és mentése
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $phone = test_input($_POST['phone']);
    $error_msg = "A kosár üres!";
    // Ellenőrizzük, hogy a kosár nem üres
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
      
    } else {
      // Rendelés létrehozása az adatbázisban
      $customer_id = create_customer($name, $email, $phone);
  
      if (!$customer_id) {
        $error_msg = "Nem sikerült létrehozni a megrendelést!";
      } else {
        $order_id = create_order($customer_id);
  
        if (!$order_id) {
          $error_msg = "Nem sikerült létrehozni a megrendelést!";
        } else {
          // Rendelt tételek hozzáadása az adatbázishoz
          foreach ($_SESSION['cart'] as $item) {
            $inventory_id = $item['inventory_id'];
            $quantity = $item['quantity'];
            if (!add_order_item($order_id, $inventory_id, $quantity)) {
              $error_msg = "Nem sikerült létrehozni a megrendelést!";
              break;
            }
          }
  
          // Ha nincs hiba, töröljük a kosarat és átirányítjuk a vásárlót a köszönőoldalra
          if (!$error_msg) {
            unset($_SESSION['cart']);
            header("Location: thanks.php");
            exit();
          }
        }
      }
    }
  }
  
  // Az adatok ellenőrzésére szolgáló függvény
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  $error_msg = "A kosár üres!";
  $name = test_input($_POST['customer_name']);
    $email = test_input($_POST['customer_email']);
    $phone = test_input($_POST['customer_phone']);
  // Az űrlap megjelenítése
  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <link rel="stylesheet" href="order.css">
    <title>Megrendelés</title>
  </head>
  <body>
    <h1>Megrendelés</h1>
    <?php if ($error_msg) { ?>
      <div style="color: red;"><?php echo $error_msg; ?></div>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="name">Név:</label><br>
  <input type="text" id="name" name="name"><br>

  <label for="email">E-mail cím:</label><br>
  <input type="text" id="email" name="email"><br>

  <label for="phone">Telefonszám:</label><br>
  <input type="text" id="phone" name="phone"><br>

  <label for="message">Üzenet:</label><br>
  <textarea id="message" name="message"></textarea><br>

  <input type="submit" value="Küldés">
</form>
  </body>
  </html>
  