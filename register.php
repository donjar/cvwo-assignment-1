<?php

session_start();
require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');

// To protect against CSRF attacks.
$form_token = base64_encode(openssl_random_pseudo_bytes(32));
$_SESSION['form_token'] = $form_token;

// Form the site, according to whether one is logged or not
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

		<link href='css/form.css' rel='stylesheet'>
		<link href='css/global.css' rel='stylesheet'>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
	</head>

	<body>
		<?php echo site_header($navbar); ?>
		<div class="col-sm-8 blog-main">
			<form class="form-signin" action="register_submit.php" method="post">
				<h2 class="form-signin-heading">Register</h2>
				<label for="email" class="sr-only">E-mail Address</label>
				<input type="email" id="email" name="email" class="form-control" placeholder="E-mail address" required autofocus>
				<label for="username" class="sr-only">Username</label>
				<input id="username" name="username" class="form-control" placeholder="Username" pattern="[a-zA-Z0-9]+" maxlength="20" required>
				<p>Usernames can only contain letters and numbers, with a maximum length of 20 characters.</p>
				<label for="password" class="sr-only">Password</label>
				<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
				<label for="repass" class="sr-only">Retype Password</label>
				<input type="password" id="repass" name="repass" class="form-control" placeholder="Retype Password" required>
				<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
				<button id="form-submit" class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
			</form>
		</div>
		<?php echo $footer; ?>
	</body>
</html>