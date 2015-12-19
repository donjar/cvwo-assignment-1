<?php

/*** begin the session ***/
session_start();

if(!isset($_SESSION['user_id']))
{
    $message = 'You must be logged in to access this page';
}
else
{
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

		$stmt_string = "SELECT phpro_username FROM users
        WHERE phpro_user_id = ?";
		$stmt = $conn->prepare($stmt_string);
		$stmt->bind_param("i", $id);

		$id = $_SESSION["user_id"];

		if ($stmt->execute()) {
	        /*** check for a result ***/
	        $stmt->bind_result($user);
	        $stmt->fetch();

	        /*** if we have no result then fail boat ***/
	        if ($user == false)
	        {
	            $message = 'Access Error';
	        }
	        /*** if we do have a result, all is well ***/
	        else
	        {
            	$message = 'Welcome '.$user;
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
<title>Members Only Page</title>
</head>
<body>
<h2><?php echo $message; ?></h2>
<a href="login.php">Re-login</a> 
</body>
</html>