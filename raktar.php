<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raktárkezelő rendszer - Készlet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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

<?php

session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}

require_once 'db_connection.php';


if (isset($_POST['logout'])) {
  unset($_SESSION['user_id']);
  session_destroy();
  header('Location: index.php');
  exit;
}
?>

<div class="container my-5">
    <h1>Készlet</h1>
    <?php
       
        $sql = "SELECT * FROM inventory ";
        $result = mysqli_query($conn, $sql);

       
        if (mysqli_num_rows($result) > 0) {
            echo '<table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Termék neve</th>
                            <th scope="col">Mennyiség</th>
                            <th scope="col">Termék leírása</th>
                        </tr>
                    </thead>
                    <tbody>';

            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                        <td>' . $row['item_name'] . '</td>
                        <td>' . $row['item_quantity'] . '</td>
                        <td>' . $row['item_description'] . '</td>
                     </tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<p class="lead">Nincsenek termékek a készleten.</p>';
        }

      
        mysqli_close($conn);
    ?>
</div>

</body>
</html>