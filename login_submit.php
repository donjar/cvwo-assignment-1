<?php

session_start();

require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');
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
		$db = new Database();

		$stmt = "SELECT * FROM _users
		WHERE username = ? OR email = ?";
		$types = "ss";
		$array_of_binds = array($username, $username);
		$result = $db->fetch($stmt, $types, $array_of_binds);
		if ($result === false) {
			$message = $db->error();
		} elseif ($result === []) {
			$message = "Username and password does not match. Please try again.";
		} else {
			$got_pass = $result[0]['password'];
			if (password_verify($unencrypted_pass, $got_pass)) {
				$something_wrong = false;
				header('refresh: 3; url = index.php');
				$message = "You have successfully logged in.";
				$_SESSION['username'] = $result[0]['username'];
				$_SESSION['user_id'] = $result[0]['ID'];
				$message .= "<br /> <a href='index.php'>Click here if you are not redirected.</a>";
			} else {
				$message = "Username and password does not match. Please try again.";
			}
		}
	}
}

if ($something_wrong) {
	$message .= "<br /> <a href='login.php'>Re-login</a>";
}

// Form the navbar
$navbar = "<a class='blog-nav-item' href='index.php'>Home</a>";
$navbar .= "<a class='blog-nav-item active' href='login.php'>Login</a>";
$navbar .= "<a class='blog-nav-item' href='register.php'>Register</a>";

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