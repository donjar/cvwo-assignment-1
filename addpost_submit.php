<?php

session_start();

require_once("config/config.php");
require_once(DIR_DATABASE . "mysqli-db-obj.php");

// There are so many ways this can go wrong. Assume it is wrong first.
$something_wrong = true;

// Variables must have been set and token form must be valid.
// Generate a generic error if not (else what error should I produce lol)
if (!isset($_POST["title"], $_POST["contents"], $_POST["form_token"]) || $_POST["form_token"] !== $_SESSION["form_token"]) {
	$message = "An error has occured. Please try again.";
} else {
	$user_id = $_SESSION['user_id'];
	$title = $_POST['title'];
	$contents = $_POST['contents'];
	$db = new Database();

	$stmt = "INSERT INTO _posts (User_ID, Title, Contents) VALUES (?, ?, ?)";
	$types = "sss";
	$array_of_binds = array($user_id, $title, $contents);
	if ($db->query($stmt, $types, $array_of_binds)) {
		$something_wrong = false;
		$message = "Post has been successfully added!";
		$message = "<br />";
		$message .= "<a href='index.php'>Go back</a>";
	} else {
		$message = $db->error();
	}
}

if ($something_wrong) {
	$message .= "<br /> <a href='addpost.php'>Re-add post</a>";
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Add Post</title>

		<link href="css/form.css" rel="stylesheet">
		<link href="css/global.css" rel="stylesheet">
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<p><?php echo $message; ?></p>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>