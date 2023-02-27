<?php
// Start a session
session_start();

/* Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header('location: login.php');
  exit;
}*/

require_once "db_connection.php";


$location_id = $item_name = $item_description = $item_quantity = $item_price = "";
$location_id_err = $item_name_err = $item_quantity_err = $item_price_err = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input_location_id = trim($_POST["location_id"]);
    if (empty($input_location_id)) {
        $location_id_err = "Please select a location.";
    } else {
        $location_id = $input_location_id;
    }

   
    $input_item_name = trim($_POST["item_name"]);
    if (empty($input_item_name)) {
        $item_name_err = "Please enter an item name.";
    } else {
        $item_name = $input_item_name;
    }

  
    $input_item_quantity = trim($_POST["item_quantity"]);
    if (empty($input_item_quantity)) {
        $item_quantity_err = "Please enter a quantity.";
    } elseif (!ctype_digit($input_item_quantity)) {
        $item_quantity_err = "Please enter a valid quantity (positive whole number).";
    } else {
        $item_quantity = $input_item_quantity;
    }

    
    $input_item_price = trim($_POST["item_price"]);
    if (empty($input_item_price)) {
        $item_price_err = "Please enter a price.";
    } elseif (!is_numeric($input_item_price) || $input_item_price <= 0) {
        $item_price_err = "Please enter a valid price (positive number).";
    } else {
        $item_price = $input_item_price;
    }

   
    $item_description = trim($_POST["item_description"]);

   
    if (empty($location_id_err) && empty($item_name_err) && empty($item_quantity_err) && empty($item_price_err)) {
        
        $sql = "INSERT INTO inventory (location_id, item_name, item_description, item_quantity, item_price) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
           
            mysqli_stmt_bind_param($stmt, "isssd", $param_location_id, $param_item_name, $param_item_description, $param_item_quantity, $param_item_price);

          
            $param_location_id = $location_id;
            $param_item_name = $item_name;
            $param_item_description = $item_description;
            $param_item_quantity = $item_quantity;
            $param_item_price = $item_price;

           
            if (mysqli_stmt_execute($stmt)) {
               
                header("location: raktar.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

           
            mysqli_stmt_close($stmt);
        }
    }

    
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="additem.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Készlet felvétele</title>
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
            <li><a class="dropdown-item" href="#">Elérhetőség</a></li>
            <li><a class="dropdown-item" href="raktar.php">Raktár</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Megrendelés</a></li>
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

    <h2>Készlet felvétele</h2>
    <p>Kérjük, töltse ki az alábbi mezőket az új készlet felvételéhez:</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label for="location_id">Helyszín:</label>
            <select name="location_id" id="location_id">
                <option value="">Válasszon helyszínt...</option>
                <!-- TODO: dinamikusan generált helyszín opciók -->
            </select>
            <span><?php echo $location_id_err; ?></span>
        </div>
        <div>
            <label for="item_name">Termék neve:</label>
            <input type="text" name="item_name" id="item_name" value="<?php echo $item_name; ?>">
            <span><?php echo $item_name_err; ?></span>
        </div>
        <div>
            <label for="item_description">Termék leírása:</label>
            <textarea name="item_description" id="item_description"><?php echo $item_description; ?></textarea>
        </div>
        <div>
            <label for="item_quantity">Mennyiség:</label>
            <input type="number" name="item_quantity" id="item_quantity" value="<?php echo $item_quantity; ?>">
            <span><?php echo $item_quantity_err; ?></span>
        </div>
        <div>
            <label for="item_price">Ár:</label>
            <input type="number" step="0.01" name="item_price" id="item_price" value="<?php echo $item_price; ?>">
            <span><?php echo $item_price_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Felvétel">
            <a href="raktar.php">Mégse</a>
        </div>
    </form>
</body>
</html>
