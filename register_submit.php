<?php

session_start();

require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');
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

		// Is the username or e-mail already there?
		$check_stmt = "SELECT * FROM _users
		WHERE username = ? OR email = ?;";
		$check_types = "ss";
		$check_array = array($username, $email);
		$check_res = $db->fetch($check_stmt, $check_types, $check_array);
		if ($check_res === false) {
			$message = $db->error();
		} elseif ($check_res !== []) {
			if (strcmp($check_res[0]['username'], $username) === 0) {
				$message = 'Username is already in use. Please try another username.';
			} elseif (strcmp($check_res[0]['email'], $email) === 0) {
				$message = 'An account is already registered with that e-mail address.';
			}
		} else {
			$stmt = "INSERT INTO _users (username, email, password)
			VALUES (?, ?, ?);";
			$types = "sss";
			$array_of_binds = array($username, $email, $pass);

			if ($db->query($stmt, $types, $array_of_binds)) {
				$something_wrong = false;
				header('refresh: 3; url = index.php');
				$message = "You have successfully registered!";
				$message .= "<br /> <a href='index.php'>Click here if you are not redirected.</a>";
			} else {
				$message = $db->error();
			}
		}
	}
}

if ($something_wrong) {
	$message .= "<br /> <a href='register.php'>Re-register</a>";
}

// Form the navbar
$navbar = "<a class='blog-nav-item' href='index.php'>Home</a>";
$navbar .= "<a class='blog-nav-item' href='login.php'>Login</a>";
$navbar .= "<a class='blog-nav-item active' href='register.php'>Register</a>";
?>

<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		
		<title>Bloghub</title>

		<link href='css/global.css' rel='stylesheet'>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
	</head>

	<body>
		<?php echo site_header($navbar); ?>
		<div class="col-sm-8 blog-main">
			<p><?php echo $message; ?></p>
		</div><!-- /.blog-main -->
		<?php echo $footer; ?>
	</body>
</html>