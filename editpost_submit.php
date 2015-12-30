<?php

session_start();

require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');
require_once(DIR_DATABASE . "mysqli-db-obj.php");

// There are so many ways this can go wrong. Assume it is wrong first.
$something_wrong = true;

// Variables must have been set and token form must be valid.
// Generate a generic error if not (else what error should I produce lol)
if (!isset($_POST["title"], $_POST["contents"], $_POST["form_token"], $_POST["post_id"], $_SESSION['user_id']) || $_POST["form_token"] !== $_SESSION["form_token"]) {
	$message = "An error has occured. Please try again.";
} else {
	$post_id = $_POST['post_id'];
	$title = $_POST['title'];
	$contents = $_POST['contents'];
	$db = new Database();

	$stmt = "UPDATE _posts
	SET Title = ?, Contents = ?
	WHERE Post_ID = ?";
	$types = "ssi";
	$array_of_binds = array($title, $contents, $post_id);
	if ($db->query($stmt, $types, $array_of_binds)) {
		$something_wrong = false;
		header('refresh: 3; url = index.php');
		$message = "Post has been successfully edited!";
		$message .= "<br /> <a href='index.php'>Click here if you are not redirected.</a>";
	} else {
		$message = $db->error();
	}
}

if ($something_wrong) {
	$message .= "<br /> <a href='editpost.php'>Re-edit post</a>";
}

// Form the navbar
$navbar = "<a class='blog-nav-item' href='index.php'>Home</a>";
$navbar .= "<a class='blog-nav-item' href='addpost.php'>Add Post</a>";
$navbar .= "<a class='blog-nav-item' href='logout.php'>Logout</a>";
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