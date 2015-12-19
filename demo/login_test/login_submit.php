<?php

/*** begin our session ***/
session_start();

/*** check if the users is already logged in ***/
if(isset( $_SESSION['user_id'] ))
{
    $message = 'Users is already logged in';
}
/*** check that both the username, password have been submitted ***/
if(!isset( $_POST['phpro_username'], $_POST['phpro_password']))
{
    $message = 'Please enter a valid username and password';
}
/*** check the username is the correct length ***/
elseif (strlen( $_POST['phpro_username']) > 20 || strlen($_POST['phpro_username']) < 4)
{
    $message = 'Incorrect Length for Username';
}
/*** check the password is the correct length ***/
elseif (strlen( $_POST['phpro_password']) > 20 || strlen($_POST['phpro_password']) < 4)
{
    $message = 'Incorrect Length for Password';
}
/*** check the username has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['phpro_username']) != true)
{
    /*** if there is no match ***/
    $message = "Username must be alpha numeric";
}
/*** check the password has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['phpro_password']) != true)
{
        /*** if there is no match ***/
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

		$stmt_string = "SELECT phpro_user_id FROM users WHERE phpro_username = ? AND phpro_password = ?";
		$stmt = $conn->prepare($stmt_string);
		$stmt->bind_param("ss", $user, $pass);

		$user = $phpro_username;
		$pass = $phpro_password;

		if ($stmt->execute()) {
	        /*** check for a result ***/
	        $stmt->bind_result($id);
	        $stmt->fetch();

	        /*** if we have no result then fail boat ***/
	        if($id == false)
	        {
	            $message = 'Login Failed';
	        }
	        /*** if we do have a result, all is well ***/
	        else
	        {
                /*** set the session user_id variable ***/
                $_SESSION['user_id'] = $id;

                /*** tell the user we are logged in ***/
                $message = 'You are now logged in';
	        }
		} else {
			$message = $stmt->error;
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
<a href="members.php">Go to lounge</a>
<a href="login.php">Re-login</a>
</body>
</html>