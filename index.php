<?php

// Initialization
session_start();
require_once("config/config.php");
require_once(DIR_DATABASE . "mysqli-db-obj.php");
if (isset($_SESSION['username'])) {
	$logged = true;
	$username = $_SESSION['username'];
} else {
	$logged = false;
}

// Check query and retrieve data
parse_str($_SERVER['QUERY_STRING'], $query_array);

$db = new Database();
$stmt = "SELECT Post_ID,
User_ID,
Title,
DATE_FORMAT(Time, '%e %b %Y %T') AS Date,
Contents FROM _posts";

if ($query_array !== []) {
	$stmt .= " WHERE";

	foreach ($query_array as $key => $value) {
		if ($key === 'Month') {
			$stmt .= " DATE_FORMAT(Time, '%M') = '$value' AND";
		} elseif ($key === 'Year') {
			$stmt .= " DATE_FORMAT(Time, '%Y') = '$value' AND";
		} else {
			$stmt .= " $key = '$value' AND";
		}
	}

	$stmt = substr($stmt, 0, -4);
}

$stmt .= " ORDER BY Time DESC";
$result = $db->simple_fetch($stmt);

// Form the site, according to whether one is logged or not
if ($logged) {
	$desc = 'Welcome to Bloghub, ' . $username . '!';
	$navbar = "<a class='blog-nav-item active' href='index.php'>Home</a>";
	$navbar .= "<a class='blog-nav-item' href='addpost.php'>Add Post</a>";
	$navbar .= "<a class='blog-nav-item' href='logout.php'>Logout</a>";
} else {
	$desc = 'Welcome to Bloghub!';
	$navbar = "<a class='blog-nav-item active' href='index.php'>Home</a>";
	$navbar .= "<a class='blog-nav-item' href='login.php'>Login</a>";
	$navbar .= "<a class='blog-nav-item' href='register.php'>Register</a>";
}

// Form the main contents
$main = "";
foreach ($result as $key => $value) {
	$each_post_id = $value['Post_ID'];
	$post_user_id = $value['User_ID'];
	$title = htmlspecialchars($value['Title']);
	$date = $value['Date'];
	$contents = htmlspecialchars($value['Contents']);
	
	$title_query = http_build_query(array('Post_ID' => $each_post_id));
	$title_with_link = "<a href='?" . $title_query . "'>" . $title . "</a>";

	$username_stmt = "SELECT username FROM _users
	WHERE ID = $post_user_id;";
	$username_array = $db->simple_fetch($username_stmt);
	$username = $username_array[0]['username'];

	$username_query = http_build_query(array('User_ID' => $post_user_id));
	$username_with_link = "<a href='?" . $username_query . "'>" . $username . "</a>";

	$main .= "<div class='blog-post'>";
	$main .= "<h2 class='blog-post-title'>$title_with_link</h2>";
	$main .= "<p class='blog-post-meta'>$date by $username_with_link</p>";
	$main .= "<p>$contents</p>";
	$main .= "</div>";
}

// Form the dates sidebar
$date_stmt = "SELECT DISTINCT DATE_FORMAT(Time, '%M %Y') AS Date,
DATE_FORMAT(Time, '%M') AS Month,
DATE_FORMAT(Time, '%Y') AS Year
FROM _posts";
$date_result = $db->simple_fetch($date_stmt);

$dates_sidebar = "";
foreach ($date_result as $key => $value) {
	$month = $value['Month'];
	$year = $value['Year'];
	$date = $value['Date'];
	$date_query = http_build_query(array('Month' => $month, 'Year' => $year));
	$date_with_link = "<a href='?" . $date_query . "'>" . $date . "</a>";
	$dates_sidebar .= "<li>$date_with_link</li>";
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Bloghub</title>

		<link href="css/global.css" rel="stylesheet">
		<link href="css/bootstrap.min.css" rel="stylesheet">
	</head>

	<body>

		<div id="blog-masthead" class="navbar-default navbar-fixed-top">
			<div class="container">
				<nav class="blog-nav">
					<?php echo $navbar; ?>
				</nav>
			</div>
		</div>

		<div class="container">

			<div class="blog-header">
				<h1 class="blog-title">Bloghub</h1>
				<p class="lead blog-description"><?php echo $desc; ?></p>
			</div>
			<div class="row">
				<div class="col-sm-8 blog-main">
					<?php echo $main; ?>
				</div><!-- /.blog-main -->

				<div class="col-sm-3 col-sm-offset-1 blog-sidebar">
					<div class="sidebar-module sidebar-module-inset">
						<h4>About</h4>
						<p>Bloghub is a site to make cool blogs. This is made for CVWO Assignment 1.</p>
					</div>
					<div class="sidebar-module">
						<h4>Archives</h4>
						<ol class="list-unstyled">
							<?php echo $dates_sidebar ?>
						</ol>
					</div>
				</div><!-- /.blog-sidebar -->
			</div><!-- /.row -->
		</div><!-- /.container -->

		<footer class="blog-footer">
			<p>Blog template from <a href="http://getbootstrap.com">Bootstrap</a> by <a href="https://twitter.com/mdo">@mdo</a>.</p>
			<p>
				<a href="#">Back to top</a>
			</p>
		</footer>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
