<?php
/*** begin our session ***/
session_start();

/*** first check that both the username, password and form token have been sent ***/
if (!isset($_POST['phpro_username'], $_POST['phpro_password'], $_POST['form_token'])) {
	$message = 'Please enter a valid username and password';
/*** check the form token is valid ***/
} elseif ($_POST['form_token'] != $_SESSION['form_token']) {
	$message = 'Invalid form submission';
/*** check the username is the correct length ***/
} elseif (strlen($_POST['phpro_username']) > 20 || strlen($_POST['phpro_username']) < 4) {
	$message = 'Incorrect Length for Username';
/*** check the password is the correct length ***/
} elseif (strlen($_POST['phpro_password']) > 20 || strlen($_POST['phpro_password']) < 4) {
	$message = 'Incorrect Length for Password';
/*** check the username has only alpha numeric characters ***/
} elseif (!ctype_alnum($_POST['phpro_username'])) {
	$message = "Username must be alpha numeric";
/*** check the password has only alpha numeric characters ***/
} elseif (!ctype_alnum($_POST['phpro_password'])) {
	$message = "Password must be alpha numeric";
} else {
	/*** if we are here the data is valid and we can insert it into database ***/
	$phpro_username = filter_var($_POST['phpro_username'], FILTER_SANITIZE_STRING);
	$unencrypted_phpro_password = filter_var($_POST['phpro_password'], FILTER_SANITIZE_STRING);

	/*** now we can encrypt the password ***/
	$phpro_password = sha1($unencrypted_phpro_password);

	$hostname = 'localhost';
	$username = 'username';
	$password = 'password';
	$dbname = 'authentication';

	$conn = new mysqli($hostname, $username, $password, $dbname);

	if ($conn->connect_errno) {
		$message = "Failed to connect to MySQL: (" . $conn->connect_errno .
			") " . $conn->connect_error;
	} else {
		$message = "Connected! <br />";

		$stmt_string = "INSERT INTO users (phpro_username, phpro_password)
		VALUES (?, ?);";
		$stmt = $conn->prepare($stmt_string);
		$stmt->bind_param("ss", $user, $pass);

		$user = $phpro_username;
		$pass = $phpro_password;

		if ($stmt->execute()) {
			$message .= "New user created!";
		} else {
			$message .= $stmt->error;
		}
		
		unset( $_SESSION['form_token'] );

		$stmt->close();
		$conn->close();
	}
}
?>

<html>
<head>
<title>PHPRO Login</title>
</head>
<body>
<p><?php echo $message; ?></p>

<a href="login.php">Login!</a> 
</body>
</html>
