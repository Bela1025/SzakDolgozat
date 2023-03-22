<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="additem.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Készlet felvétele</title>
<link rel="stylesheet" type="text/css" href="additem.css">
	<title>Készlet felvétel</title>
</head>
<body>
<nav class="navbar navbar-dark bg-primary" style="background-color: #e3f2fd;">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Tihanyi-Tb Kft.</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="dashboard.php">Főoldal</a>
        </li>
        <li class="nav-item">

        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Lehetőségek
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="contact.html">Elérhetőség</a></li>
            <li><a class="dropdown-item" href="raktar.php">Raktár</a></li>
            <li><a class="dropdown-item" href="add_item.php">Készlet felvétel</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="order.php">Megrendelés</a></li>
          </ul>
           <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          Hírdetéseink
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="https://www.jofogas.hu/magyarorszag?account_list_id=92ab10f73289a2a0263eaa836ff8ff6f">Jófogás</li>
          <li><a class="dropdown-item" href="https://www.ebay.com/str/tihanyitbstore">Ebay</a></li>  
        </ul>
        </li>
        <li class="nav-item">

        </li>
      </ul>
      <ul class="navbar-nav me-0 mb-2 mb-lg-0"> 
        <li class="nav-item" >
          <form method="POST" action="">
            <input type="submit" name="logout" value="Kijelentkezés">
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>
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
		<label for="item_image">Kép:</label>
    <input type="file" id="item_image" name="item_image">
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


$stmt = mysqli_prepare($conn, "INSERT INTO inventory (item_name, item_description, item_quantity, item_price, location_id, item_image) VALUES (?, ?, ?, ?, ?, ?)");



// Get values submitted by the form
$item_name = $_POST['item_name'];
$item_description = $_POST['item_description'];
$item_quantity = $_POST['item_quantity'];
$item_price = $_POST['item_price'];
$location_id = $_POST['location_id'];

// Check if a file was uploaded
if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
  // Get file data
  $file_name = $_FILES['item_image']['name'];
  $file_size = $_FILES['item_image']['size'];
  $file_tmp = $_FILES['item_image']['tmp_name'];
  $file_type = $_FILES['item_image']['type'];

  // Move uploaded file to a permanent location
  $target_dir = "C:\xampp\htdocs\SzakDolgozat\images\\";
  $target_file = $target_dir . basename($file_name);
  move_uploaded_file($file_tmp, $target_file);

  // Save file path to database
  $image_path = $target_file;
} else {
  // No file was uploaded
  $image_path = "";
}

// Load database configuration and connect to database
require_once 'db_connection.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$stmt = mysqli_prepare($conn, "INSERT INTO inventory (item_name, item_description, item_quantity, item_price, location_id, item_image) VALUES (?, ?, ?, ?, ?, ?)");

// Bind parameters to the SQL statement
mysqli_stmt_bind_param($stmt, "ssidis", $item_name, $item_description, $item_quantity, $item_price, $location_id, $image_path);

if (mysqli_stmt_execute($stmt)) {
  echo "A termék sikeresen felvéve a készletbe.";
} else {
  echo "Hiba történt a termék felvétele közben: " . mysqli_error($conn);
}

// Close the prepared statement and database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);


?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>