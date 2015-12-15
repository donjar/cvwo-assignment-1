<?php

$hostname = "localhost";
$username = "username";
$password = "password";
$database = "test";

$mysqli_obj = @new mysqli($hostname, $username, $password, $database);

if ($mysqli_obj->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli_obj->connect_errno .
		") " . $mysqli_obj->connect_error;
} else {
	echo "Connected! <br />";

	$stmt = "DROP TABLE IF EXISTS number_table;";
	$stmt .= "CREATE TABLE number_table (
		num int NOT NULL,
		square int NOT NULL,
		cube int NOT NULL,
		sine float NOT NULL,
		cosine float NOT NULL,
		PRIMARY KEY (num)
		);";

	if ($mysqli_obj->multi_query($stmt)) {
		echo $stmt . " successfully run!";
	} else {
		echo $stmt . "<br />" . $mysqli_obj->error;
	}

	$mysqli_obj->close();
}

?>