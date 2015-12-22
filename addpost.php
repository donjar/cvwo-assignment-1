<?php

session_start();

// To protect against CSRF attacks.
$form_token = base64_encode(openssl_random_pseudo_bytes(32));
$_SESSION['form_token'] = $form_token;
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
		<div class="container">
		<form class="form-signin" action="addpost_submit.php" method="post">
			<h2 class="form-signin-heading">Add Post</h2>
			<label for="title" class="sr-only">Title</label>
			<input id="title" class="form-control" name="title" placeholder="Title of the post" required autofocus>
			<label for="title" class="sr-only">Contents</label>
			<textarea id="contents" name="contents" placeholder="Contents of the post" required></textarea>
			<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
			<button id="form-submit" class="btn btn-lg btn-primary btn-block" type="submit">Add Post</button>
		</form>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>