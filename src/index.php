<?php

// Initialization
session_start();
require_once('config/config.php');
require_once(DIR_COMMON . 'common.php');
require_once(DIR_COMMON . 'Parsedown.php');
require_once(DIR_DATABASE . 'mysqli-db-obj.php');
$db = new Database();

$parsedown = new Parsedown();
$parsedown->setBreaksEnabled(true);
$parsedown->setMarkupEscaped(true);

// Check query url
parse_str($_SERVER['QUERY_STRING'], $query_array);

// Form SQL statement and retrieve data
$stmt = "SELECT Post_ID,
User_ID,
Title,
DATE_FORMAT(Time, '%e %b %Y %T') AS Date,
Contents FROM _posts";

$types = '';
$array_of_binds = [];

if ($query_array !== []) {
	$condition_stmt = " WHERE";

	foreach ($query_array as $key => $value) {
		if ($key === 'Month') {
			$condition_stmt .= " DATE_FORMAT(Time, '%M') = ? AND";
			$types .= 's';
			$array_of_binds[] = $value;
		} elseif ($key === 'Year') {
			$condition_stmt .= " DATE_FORMAT(Time, '%Y') = ? AND";
			$types .= 'i';
			$array_of_binds[] = $value;
		} elseif ($key !== 'start') {
			$condition_stmt .= " $key = ? AND";
			$types .= 'i';
			$array_of_binds[] = $value;
		}
	}

	$condition_stmt = substr($condition_stmt, 0, -4); // Remove the final AND
	$stmt .= $condition_stmt;
}

$stmt .= " ORDER BY Time DESC LIMIT 5";

$start = 0;
if (isset($query_array['start'])) {
	$start = $query_array['start'];
	$stmt .= ' OFFSET ?';
	$types .= 'i';
	$array_of_binds[] = $start;
}

if ($types === '') {
	$result = $db->simple_fetch($stmt);
} else {
	$result = $db->fetch($stmt, $types, $array_of_binds);
}

// Form the navbar accordingly
if ($logged) {
	$navbar = "<a class='blog-nav-item active' href='index.php'>Home</a>";
	$navbar .= "<a class='blog-nav-item' href='addpost.php'>Add Post</a>";
	$navbar .= "<a class='blog-nav-item' href='logout.php'>Logout</a>";
} else {
	$navbar = "<a class='blog-nav-item active' href='index.php'>Home</a>";
	$navbar .= "<a class='blog-nav-item' href='login.php'>Login</a>";
	$navbar .= "<a class='blog-nav-item' href='register.php'>Register</a>";
}

// Form the main contents
$main = "";

// Display heading of user's posts, if requested
$username_stmt = "SELECT username FROM _users
WHERE ID = ?;";
$username_types = "i";

if (isset($query_array['User_ID'])) {
	$username_array_of_binds = array($query_array['User_ID']);
	$username_array = $db->fetch($username_stmt, $username_types, $username_array_of_binds);
	$username = $username_array[0]['username'];
	$main .= "<h2>Posts by " . $username . "</h2>";
}

// Display heading of certain month, if requested
if (isset($query_array['Month'], $query_array['Year'])) {
	$main .= "<h2>Posts made in " . $query_array['Month'] . " " . $query_array['Year'] . "</h2>";
}

foreach ($result as $key => $value) {
	$each_post_id = $value['Post_ID'];
	$post_user_id = $value['User_ID'];
	$title = $value['Title'];
	$date = $value['Date'];
	$unparsed_contents = $value['Contents'];
	$contents = $parsedown->text($unparsed_contents);
	
	$title_with_link = query_in_link('Post_ID', $each_post_id, $title);

	$username_array_of_binds = array($post_user_id);
	$username_array = $db->fetch($username_stmt, $username_types, $username_array_of_binds);
	$username = $username_array[0]['username'];

	$username_with_link = query_in_link('User_ID', $post_user_id, $username);

	$main .= "<div class='blog-post'>";
	$main .= "<h2 class='blog-post-title dont-break-out'>$title_with_link</h2>";
	$main .= "<p class='blog-post-meta'>$date by $username_with_link</p>";
	$main .= "<div class='dont-break-out'>$contents</div>";
	$main .= "</div>";
}

// If only the Post ID is selected and username matches, allow post modification
if (isset($query_array['Post_ID'], $_SESSION['user_id']) && $_SESSION['user_id'] === $post_user_id) {
	// Remove the </div>
	$main = substr($main, 0, -6);
	$main .= '
			<a class="btn btn-default" href="editpost.php?Post_ID=' . $query_array['Post_ID'] . '">Edit Post</a>
			<a class="btn btn-danger" onclick="return delete_post()">Delete post</a>
	';
	$main .= "</div>";
}

// Check amount of posts and form the pagination accordingly
$count_stmt = "SELECT COUNT(*) AS Result FROM _posts";
$count_stmt .= isset($condition_stmt) ? $condition_stmt : '';
$count_array = $db->simple_fetch($count_stmt);
$count = $count_array[0]['Result'];

if ($start != 0) {
	$prev = ($start < 5) ? 0 : ($start - 5);
	$prev_with_link = '<li id="previous-pager">' . query_in_link('start', $prev, 'Previous', true) . '</li>';
} else {
	$prev_with_link = '<li id="previous-pager" class="disabled"><a>Previous</a></li>';
}
if ($count - $start > 5) {
	$next_with_link = '<li>' . query_in_link('start', $start + 5, 'Next', true) . '</li>';
} else {
	$next_with_link = '<li class="disabled"><a>Next</a></li>';
}
$main .= '

<nav>
	<ul class="pager">
	' . $prev_with_link . $next_with_link . '
	</ul>
</nav>

';
?>


<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='utf-8'>
		<meta http-equiv='X-UA-Compatible' content='IE=edge'>
		<meta name='viewport' content='width=device-width, initial-scale=1'>
		
		<title>Bloghub</title>

		<link href='css/global.css' rel='stylesheet'>
		<link href='css/bootstrap.min.css' rel='stylesheet'>
		<script type="text/javascript" src="js/delete.js"></script>
	</head>

	<body>
		<?php echo site_header($navbar); ?>
		<div class="col-sm-8 blog-main">
			<?php echo $main; ?>
		</div><!-- /.blog-main -->
		<?php echo $footer; ?>
	</body>
</html>