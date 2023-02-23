<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
	<title>Tihanyi-Tb Kft.</title>
</head>
<body>
	<h2>Regisztráció</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="username">Felhasználónév:</label>
		<input type="text" id="username" name="username"><br><br>
		<label for="password">Jelszó:</label>
		<input type="password" id="password" name="password"><br><br>
		<label for="confirm_password">Jelszó megerősítés:</label>
		<input type="password" id="confirm_password" name="confirm_password"><br><br>
		<label for="email">Email:</label>
		<input type="email" id="email" name="email"><br><br>
		<input type="submit" value="Regisztrálás" >
	</form>

	<?php
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$username = $_POST["username"];
		$password = $_POST["password"];
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$confirm_password = $_POST["confirm_password"];
		$email = $_POST["email"];
		
		
		if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
			echo "<p>Kérem töltse ki az összes mezőt!</p>";
		} elseif ($password != $confirm_password) {
			echo "<p>Jelszók nem egyeznek!</p>";
		} else {
			
			$host = "localhost";
			$db_username = "admin";
			$db_password = "jF/rfVlPIJHSjGPc";
			$db_name = "c31vargabeladb";
			$conn = mysqli_connect($host, $db_username, $db_password, $db_name);
			header('Location: login.php');
			
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
			}
			
			// Prepare the SQL statement to insert the user data into the database
			$sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

			// Execute the SQL statement
			if (mysqli_query($conn, $sql)) {
				echo "<p>Registration successful!</p>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			}

			// Close the database connection
			mysqli_close($conn);
		}
	}
	?>
</body>
</html>