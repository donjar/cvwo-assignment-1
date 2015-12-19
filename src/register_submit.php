<?php

session_start();

require_once("vendors/config/config.php");
require_once(DIR_DATABASE . "mysqli-db-obj.php");

// There are so many ways this can go wrong. Assume it is wrong first.
$something_wrong = true;

// Variables must have been set and token form must be valid.
// Generate a generic error if not (else what error should I produce lol)
if (!isset($_POST["email"], $_POST["username"], $_POST["password"], $_POST["repass"], $_POST["form_token"]) || $_POST["form_token"] !== $_SESSION["form_token"]) {
	$message = "An error has occured. Please try again.";
} else {
	$username = $_POST["username"];
	$email = $_POST["email"];
	$unencrypted_pass = $_POST["password"];
	// Check username.
	if ($username === "") {
		$message = "Please enter a username.";
	} elseif (!ctype_alnum($username)) {
		$message = "Username can only contain letters and/or numbers.";
	} elseif (!strlen($username) > 20) {
		$message = "Username can only have a maximum length of 20 characters.";
	// Check email.
	} elseif ($email === "") {
		$message = "Please enter an e-mail address.";
	// Check password.
	} elseif ($unencrypted_pass === "") {
		$message = "Please enter a password.";
	} elseif ($unencrypted_pass !== $_POST["repass"]) {
		$message = "Passwords must match. Try again.";
	// Everything OK. Query the database.
	} else {
		$pass = password_hash($unencrypted_pass, PASSWORD_DEFAULT);
		$db = new Database();

		$stmt = "INSERT INTO _users (username, email, password)
		VALUES (?, ?, ?);";
		$types = "sss";
		$array_of_binds = array($username, $email, $pass);
		if ($db->query($stmt, $types, $array_of_binds)) {
			$something_wrong = false;
			$message = "An e-mail has been sent to your e-mail address. Please click the link inside the e-mail to verify your e-mail address.";
			// Temporary
			$message .= "<br /> <a href='login.php'>Login</a>";
		} else {
			$message = $db->error();
		}
	}
}

if ($something_wrong) {
	$message .= "<br /> <a href='register.php'>Re-register</a>";
}

?>

<html>
<head>
	<title>Register to Bloghub</title>
</head>
<body>
	<p><?php echo $message; ?></p>
</body>
</html>