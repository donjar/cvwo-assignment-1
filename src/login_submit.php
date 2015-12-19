<?php

session_start();

require_once("vendors/config/config.php");
require_once(DIR_DATABASE . "mysqli-db-obj.php");

// There are so many ways this can go wrong. Assume it is wrong first.
$something_wrong = true;

// Variables must have been set and token form must be valid.
// Generate a generic error if not (else what error should I produce lol)
if (!isset($_POST["username"], $_POST["password"], $_POST["form_token"]) || $_POST["form_token"] !== $_SESSION["form_token"]) {
	$message = "An error has occured. Please try again.";
} else {
	$username = $_POST["username"];
	$unencrypted_pass = $_POST["password"];
	// Check username.
	if ($username === "") {
		$message = "Please enter a username or email.";
	// Check password.
	} elseif ($unencrypted_pass === "") {
		$message = "Please enter a password.";
	// Everything OK. Query the database.
	} else {
		$pass = password_hash($unencrypted_pass, PASSWORD_DEFAULT);
		$db = new Database();

		$stmt = "SELECT ID FROM _users
		WHERE ((username = ? OR email = ?) AND password = ?)";
		$types = "sss";
		$array_of_binds = array($username, $username, $pass);
		$result = $db->fetch($stmt, $types, $array_of_binds);
		if ($result === false) {
			$message = $db->error();
		} elseif ($result === []) {
			$message = "Username and password does not match. Please try again.";
		} else {
			$something_wrong = false;
			$message = "You have successfully logged in.";
			// Temporary
			$message .= "<br /> <a href='index.php'>Index</a>";			
		}
	}
}

if ($something_wrong) {
	$message .= "<br /> <a href='login.php'>Re-login</a>";
}

?>

<html>
<head>
	<title>Login to Bloghub</title>
</head>
<body>
	<p><?php echo $message; ?></p>
</body>
</html>