<?php

session_start();
require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');

// To protect against CSRF attacks.
$form_token = base64_encode(openssl_random_pseudo_bytes(32));
$_SESSION['form_token'] = $form_token;

// Form the site, according to whether one is logged or not
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

		<link href='css/form.css' rel='stylesheet'>
		<link href='css/global.css' rel='stylesheet'>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
	</head>

	<body>
		<?php echo site_header($navbar); ?>
		<div class="col-sm-8 blog-main">
			<form class="form-signin" action="login_submit.php" method="post">
				<h2 class="form-signin-heading">Login</h2>
				<label for="username" class="sr-only">Username or E-mail Address</label>
				<input id="username" name="username" class="form-control" placeholder="Username or e-mail address" required autofocus>
				<label for="password" class="sr-only">Password</label>
				<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
				<!--
				<div class="checkbox">
					<label>
						<input type="checkbox" value="remember-me"> Remember me
					</label>
				</div>
				-->
				<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
				<button id="form-submit" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
			</form>
		</div>
		<?php echo $footer; ?>
	</body>
</html>