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
		
		<title>Register to Bloghub</title>

		<link href="css/form.css" rel="stylesheet">
		<link href="css/global.css" rel="stylesheet">
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>

	<body>
		<div class="container">
		<form class="form-signin" action="register_submit.php" method="post">
			<h2 class="form-signin-heading">Register</h2>
			<label for="email" class="sr-only">E-mail Address</label>
			<input type="email" id="email" name="email" class="form-control" placeholder="E-mail address" required autofocus>
			<label for="username" class="sr-only">Username</label>
			<input id="username" name="username" class="form-control" placeholder="Username" pattern="[a-zA-Z0-9]+" maxlength="20" required>
			<label for="password" class="sr-only">Password</label>
			<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
			<label for="repass" class="sr-only">Retype Password</label>
			<input type="password" id="repass" name="repass" class="form-control" placeholder="Retype Password" required>
			<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
			<button id="form-submit" class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
		</form>
		</div>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>