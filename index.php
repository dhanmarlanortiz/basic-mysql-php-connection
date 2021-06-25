<!DOCTYPE HTML>  
<html>
<head>
	<link rel="stylesheet" href="css/main.css">	
</head>
<body>  

	<?php
		$name = $email = $message = "";
		$nameErr = $emailErr = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			if ( empty($_POST["name"]) ) {
				$nameErr = "Name is required";
			} else {
				$name = clean($_POST["name"]);

				if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
					$nameErr = "Only letters and white space allowed";
				}
			}

			if ( empty($_POST["email"]) ) {
				$emailErr = "Email is required";
			} else {
				$email = clean($_POST["email"]);
    
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$emailErr = "Invalid email format";
				}
			}

			if ( empty($_POST["message"]) ) {

			} else {
				$message = clean($_POST["message"]);
			}

			if( empty($nameErr) && empty($emailErr) ) {
				$servername = "localhost";
				$username = "root";
				$password = "";
				$dbname = "contacts";

				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);

				// Check connection
				if ($conn->connect_error) {
				  die("Connection failed: " . $conn->connect_error);
				}

				$sql = "INSERT INTO customers (name, email, message) VALUES ('$name', '$email', '$message')";

				if ($conn->query($sql) === TRUE) {

				  header("Location: " . $_SERVER["PHP_SELF"] . "?success=Your%20message%20has%20been%20successfully%20sent.");
				} else {
				  echo "Error: " . $sql . "<br>" . $conn->error;
				}

				$conn->close();
			}
		}

		function clean($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}

	?>

<div class="container">

		<h2>Contact Us</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

			<div>
				<label for="">Complete name <span>*</span></label>
				<input type="text" name="name" value="<?php echo $name; ?>" placeholder="Your name">
				<p class="error">
					<?php echo $nameErr; ?>
				</p>
			
			</div>

			<div>
				<label for="">Email <span>*</span></label>
				<input type="text" name="email" value="<?php echo $email; ?>" placeholder="Email address">
				<p class="error">
					<?php echo $emailErr; ?>
				</p>
			</div>

			<div>
				<label for="">Message</label>
				<textarea name="message" rows="4" cols="40" placeholder="Say something"><?php echo $message; ?></textarea>
			</div>

			<input type="submit" name="submit" value="Submit">  
		</form>

		<?php
			if(isset($_GET['success'])) {
				echo '<br><p class="message-success">' . $_GET['success'] . '</p>';
			}
		?>

	</div>

	<div class="result-container">
			<?php
				echo "<pre>";
				echo "POST: ";
				print_r($_POST);
				echo "<br>";
				echo "Complete name: $name<br>";
				echo "Email: $email<br>";
				echo "Message: $message<br>";
				echo "<br>";
				echo "Complete name error: $nameErr<br>";
				echo "Email error: $emailErr<br>";
				echo "</pre>";
			?>
	</div>
</body>
</html>