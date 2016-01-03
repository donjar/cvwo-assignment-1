<?php

session_start();
require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');

// Form the site, according to whether one is logged or not
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

		<link href='css/form.css' rel='stylesheet'>
		<link href='css/global.css' rel='stylesheet'>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
	</head>

	<body>
		<?php echo site_header($navbar); ?>
		<div class="col-sm-8 blog-main">
			<p>Under construction.</p>
		</div>
		<?php echo $footer; ?>
	</body>
</html>