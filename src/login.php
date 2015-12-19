<?php

session_start();

// To protect against CSRF attacks.
$form_token = base64_encode(openssl_random_pseudo_bytes(32));
$_SESSION['form_token'] = $form_token;
?>

<html>
	<head>
		<title>Login to Bloghub</title>
	</head>

	<body>
		<h1>Login</h1>
		<form action="login_submit.php" method="post">
			<fieldset>
				<p>
					<label for="email">Username or E-mail</label>
					<input type="text" id="username" name="username" value="" />
				</p>
					<label for="password">Password</label>
					<input type="password" id="password" name="password" value="" />
				</p>
				<p>
					<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
					<input type="submit" value="Login" />
				</p>
			</fieldset>
		</form>
	</body>
</html>