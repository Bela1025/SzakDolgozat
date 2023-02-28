<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="additem.css">
	<title>Készlet felvétel</title>
</head>
<body>
	<h1>Készlet felvétel</h1>
	<form action="add_item.php" method="post">
		<label for="item_name">Termék neve:</label>
		<input type="text" id="item_name" name="item_name" required><br><br>
		<label for="item_description">Termék leírása:</label>
		<textarea id="item_description" name="item_description"></textarea><br><br>
		<label for="item_quantity">Mennyiség:</label>
		<input type="number" id="item_quantity" name="item_quantity" min="1" required><br><br>
		<label for="item_price">Egységár:</label>
		<input type="number" id="item_price" name="item_price" min="0.01" step="0.01" required><br><br>
		<label for="location_id">Raktár:</label>
		<select id="location_id" name="location_id" required>
			<option value="" disabled selected>Válassz raktárat</option>
			
      <?php
			// Load database configuration and connect to database
			require_once 'db_connection.php';
      
			
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
    
			// Fetch locations from the database
			$sql = "SELECT location_id, location_name FROM locations ORDER BY location_name";
			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				while ($row = mysqli_fetch_assoc($result)) {
					echo "<option value=\"{$row['location_id']}\">{$row['location_name']}</option>";
				}
			}

			
			
      ?>
      </select><br><br>
		<input type="submit" value="Készlet felvétele">
      <?php
// Load database configuration and connect to database
require_once 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prepare SQL statement to insert new item into the database
$sql = "INSERT INTO inventory (item_name, item_description, item_quantity, item_price, location_id) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// Bind parameters to the SQL statement
mysqli_stmt_bind_param($stmt, "ssidi", $item_name, $item_description, $item_quantity, $item_price, $location_id);

// Get values submitted by the form
$item_name = $_POST['item_name'];
$item_description = $_POST['item_description'];
$item_quantity = $_POST['item_quantity'];
$item_price = $_POST['item_price'];
$location_id = $_POST['location_id'];

// Execute the SQL statement
if (mysqli_stmt_execute($stmt)) {
    echo "A termék sikeresen felvéve a készletbe.";
} else {
    echo "Hiba történt a termék felvétele közben: " . mysqli_error($conn);
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

		
	</form>
</body>
</html>
