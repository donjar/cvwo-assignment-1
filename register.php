<?php

session_start();

// To protect against CSRF attacks.
$form_token = base64_encode(openssl_random_pseudo_bytes(32));
$_SESSION['form_token'] = $form_token;
?>

<html>
	<head>
		<title>Register to Bloghub</title>
	</head>

	<body>
		<h1>Register</h1>
		<form action="register_submit.php" method="post">
			<fieldset>
				<p>
					<label for="email">E-mail</label>
					<input type="text" id="email" name="email" value="" />
				<p>
				<p>
					<label for="username">Username</label>
					<input type="text" id="username" name="username" value="" maxlength="20" />
					<br>
					Usernames can only contain letters and numbers, with a maximum length of 20.
				</p>
					<label for="password">Password</label>
					<input type="password" id="password" name="password" value="" />
				</p>
				<p>
					<label for="repass">Retype password</label>
					<input type="password" id="repass" name="repass" value="" />
				</p>
				<p>
					<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
					<input type="submit" value="Register" />
				</p>
			</fieldset>
		</form>
	</body>
</html>