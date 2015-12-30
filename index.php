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

if ($query_array !== []) {
	$condition_stmt = " WHERE";

	foreach ($query_array as $key => $value) {
		if ($key === 'Month') {
			$condition_stmt .= " DATE_FORMAT(Time, '%M') = '$value' AND";
		} elseif ($key === 'Year') {
			$condition_stmt .= " DATE_FORMAT(Time, '%Y') = '$value' AND";
		} elseif ($key !== 'start') {
			$condition_stmt .= " $key = '$value' AND";
		}
	}

	$condition_stmt = substr($condition_stmt, 0, -4); // Remove the final AND
	$stmt .= $condition_stmt;
}

$stmt .= " ORDER BY Time DESC LIMIT 5";

$start = 0;
if (isset($query_array['start'])) {
	$start = $query_array['start'];
	$stmt .= ' OFFSET ' . $start;
}

$result = $db->simple_fetch($stmt);

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
foreach ($result as $key => $value) {
	$each_post_id = $value['Post_ID'];
	$post_user_id = $value['User_ID'];
	$title = $value['Title'];
	$date = $value['Date'];
	$unparsed_contents = $value['Contents'];
	$contents = $parsedown->text($unparsed_contents);
	
	$title_with_link = query_in_link('Post_ID', $each_post_id, $title);

	$username_stmt = "SELECT username FROM _users
	WHERE ID = $post_user_id;";
	$username_array = $db->simple_fetch($username_stmt);
	$username = $username_array[0]['username'];

	$username_with_link = query_in_link('User_ID', $post_user_id, $username);

	$main .= "<div class='blog-post'>";
	$main .= "<h2 class='blog-post-title dont-break-out'>$title_with_link</h2>";
	$main .= "<p class='blog-post-meta'>$date by $username_with_link</p>";
	$main .= "<p class='dont-break-out'>$contents</p>";
	$main .= "</div>";
}

// If only the Post ID is selected and username matches, allow post modification
if (isset($query_array['Post_ID'], $_SESSION['user_id']) && $_SESSION['user_id'] === $post_user_id) {
	$contents_to_be_sent = htmlspecialchars(htmlspecialchars(str_replace("\n", '<br />',$unparsed_contents)));
	// Remove the </div>
	$main = substr($main, 0, -6);
	$main .= '
			<form action="editpost.php" method="post">
				<input type="hidden" name="Post_ID" value=' . $each_post_id . ' />
				<input type="hidden" name="Title" value=' . $title . ' />
				<input type="hidden" name="Contents" value=' . $contents_to_be_sent . ' />
				<button class="btn btn-default" type="submit">Edit Post</button>
				<button type="button" class="btn btn-danger" onclick="return delete_post()" href="delete.php">Delete post</button>
			</form>
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