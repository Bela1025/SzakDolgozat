<?php
// Include the database connection file
include_once 'db_connection.php';

// Define variables and initialize with empty values
$item_name = $item_description = $item_quantity = $item_price = $location_id = "";
$item_name_err = $item_quantity_err = $item_price_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate item name
    if (empty(trim($_POST["item_name"]))) {
        $item_name_err = "Kérjük, adja meg a termék nevét.";
    } else {
        $item_name = trim($_POST["item_name"]);
    }

    // Validate item quantity
    if (empty(trim($_POST["item_quantity"]))) {
        $item_quantity_err = "Kérjük, adja meg a termék mennyiségét.";
    } else {
        $item_quantity = trim($_POST["item_quantity"]);
    }

    // Validate item price
    if (empty(trim($_POST["item_price"]))) {
        $item_price_err = "Kérjük, adja meg a termék árát.";
    } else {
        $item_price = trim($_POST["item_price"]);
    }

    // Get location ID
    $location_id = $_POST["location_id"];

    // Check input errors before inserting in database
    if (empty($item_name_err) && empty($item_quantity_err) && empty($item_price_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO inventory (location_id, item_name, item_description, item_quantity, item_price) 
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssd", $location_id, $item_name, $item_description, $item_quantity, $item_price);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to inventory page
                header("location: inventory.php");
                exit();
            } else {
                echo "Oops! Valami hiba történt. Kérjük, próbálja újra később.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Készlet felvétel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
 
<div class="container my-4">
    <h1 class="mb-4">Készlet felvétel</h1>
    <form action="add_item.php" method="POST">
        <div class="form-group">
            <label for="location_id">Raktár helye:</label>
            <select class="form-control" id="location_id" name="location_id">
                <option value="">Válassz raktárat</option>
                <?php
                // Connect to the database
                $conn = mysqli_connect('localhost', 'username', 'password', 'database_name');

                // Check if the connection was successful
                if (!$conn) {
                    die('Could not connect to the database: ' . mysqli_connect_error());
                }

                // Query the database to get the list of locations
                $sql = "SELECT * FROM locations";
                $result = mysqli_query($conn, $sql);

                // Check if there are any locations
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each row in the result set and add an option for each location
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['location_id'] . '">' . $row['location_name'] . '</option>';
                    }
                } else {
                    echo '<option disabled>Nincs elérhető raktár.</option>';
                }

                // Close the database connection
                mysqli_close($conn);
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="item_name">Termék neve:</label>
            <input type="text" class="form-control" id="item_name" name="item_name" required>
        </div>
        <div class="form-group">
            <label for="item_description">Termék leírása:</label>
            <textarea class="form-control" id="item_description" name="item_description"></textarea>
        </div>
        <div class="form-group">
            <label for="item_quantity">Mennyiség:</label>
            <input type="number" class="form-control" id="item_quantity" name="item_quantity" required>
        </div>
        <div class="form-group">
            <label for="item_price">Ár:</label>
            <input type="number" class="form-control" id="item_price" name="item_price" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Felvitel</button>
    </form>
</div>

</body>
</html>
