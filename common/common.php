<?php

require_once('config/config.php');
require_once(DIR_DATABASE . 'mysqli-db-obj.php');
$db = new Database();

if (isset($_SESSION['username'])) {
	$logged = true;
	$username = $_SESSION['username'];
} else {
	$logged = false;
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

// Form the site, according to whether one is logged or not
if ($logged) {
	$desc = 'Welcome to Bloghub, ' . $username . '!';
} else {
	$desc = 'Welcome to Bloghub!';
}

function site_header($navbar) {
	global $desc;
	return
"
		<div id='blog-masthead' class='navbar-default navbar-fixed-top'>
			<div class='container'>
				<nav class='blog-nav'>"
					. $navbar .
				"</nav>
			</div>
		</div>

		<div class='container'>

			<div class='blog-header'>
				<h1 class='blog-title'>Bloghub</h1>
				<p class='lead blog-description'>" . $desc . "</p>
			</div>

			<div class='row'>

";
}

$footer = 
"

				<div class='col-sm-3 col-sm-offset-1 blog-sidebar'>
					<div class='sidebar-module sidebar-module-inset'>
						<h4>About</h4>
						<p>Bloghub is a site to make cool blogs. This is made for CVWO Assignment 1.</p>
					</div>
					<div class='sidebar-module'>
						<h4>Archives</h4>
						<ol class='list-unstyled'>"
							. $dates_sidebar .
						"</ol>
					</div>
				</div><!-- /.blog-sidebar -->
			</div><!-- /.row -->
		</div><!-- /.container -->

		<footer class='blog-footer'>
			<p>Blog template from <a href='http://getbootstrap.com'>Bootstrap</a> by <a href='https://twitter.com/mdo'>@mdo</a>.</p>
			<p>
				<a href='#'>Back to top</a>
			</p>
		</footer>

		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
		<script>window.jQuery || document.write('<script src=\"../../assets/js/vendor/jquery.min.js\"><\/script>')</script>
		<script src='js/bootstrap.min.js'></script>

";

function query_in_link($key, $value, $text, $append = false) {
	if ($append) {
		parse_str($_SERVER['QUERY_STRING'], $query_array);
		$query_array[$key] = $value;
	} else {
		$query_array = array($key => $value);
	}
	$query = http_build_query($query_array);
	$query_with_link = "<a href='?" . $query . "'>" . $text . "</a>";
	return $query_with_link;
}