<?php

session_start();
require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');
require_once(DIR_DATABASE . "mysqli-db-obj.php");
require_once(DIR_DATABASE . 'mysqli-db-obj.php');
$db = new Database();

// There are so many ways this can go wrong. Assume it is wrong first.
$something_wrong = true;

// To protect against CSRF attacks.
$form_token = base64_encode(openssl_random_pseudo_bytes(32));
$_SESSION['form_token'] = $form_token;

$post_id = $_GET['Post_ID'];

// Connect to the database and retrieve the post.
$stmt = "SELECT Title, Contents FROM _posts WHERE Post_ID = ?";
$types = "i";
$array_of_binds = array($post_id);
$result = $db->fetch($stmt, $types, $array_of_binds);
if ($result !== false) {
	$title = $result[0]['Title'];
	$contents = $result[0]['Contents'];
	$something_wrong = false;
}

// Form the site, according to whether one is logged or not
$navbar = "<a class='blog-nav-item' href='index.php'>Home</a>";
$navbar .= "<a class='blog-nav-item' href='addpost.php'>Add Post</a>";
$navbar .= "<a class='blog-nav-item' href='logout.php'>Logout</a>";

// Form main content
if ($something_wrong) {
	$main = $db->error();
} else {
	$main = '
		<div class="col-sm-8 blog-main">
			<form id="form-addpost" class="form-signin" action="editpost_submit.php" method="post">
				<h2 class="form-signin-heading">Edit Post</h2>
				<label for="title" class="sr-only">Title</label>
				<input value=' . $title . ' id="title" class="form-control" name="title" placeholder="Title of the post" maxlength="255" required autofocus>
				<p>The title can only contain a maximum of 255 characters.</p>
				<label for="contents" class="sr-only">Contents</label>
				<textarea id="contents" class="form-control" name="contents" placeholder="Contents of the post" required>' . $contents . '</textarea>
				<p>Github Markdown formatting is supported. Check this <a href="https://guides.github.com/features/mastering-markdown/">link</a> for details.</p>
				<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
				<input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
				<button id="form-submit" class="btn btn-lg btn-primary btn-block" type="submit">Edit Post</button>
			</form>
		</div><!-- /.blog-main -->
	';
}
?>

<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		
		<title>Bloghub</title>

		<link href='css/form.css' rel='stylesheet'>
		<link href='css/global.css' rel='stylesheet'>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
	</head>

	<body>
		<?php echo site_header($navbar); ?>
		<?php echo $main; ?>
		<?php echo $footer; ?>
	</body>
</html>