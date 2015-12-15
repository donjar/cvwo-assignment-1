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

	$insert_stmt = "INSERT INTO number_table VALUES (?, ?, ?, ?, ?);";
	$insert_run = $mysqli_obj->prepare($insert_stmt);
	$insert_run->bind_param("iiidd", $int, $sq, $cube, $s, $c);

	for ($num = 1; $num <= 100; $num++) {
		$int = $num;
		$sq = $num * $num;
		$cube = $num * $num * $num;
		$s = sin($num);
		$c = cos($num);
		$insert_run->execute();
	}

	echo "New records created!";
	
	$insert_run->close();
	$mysqli_obj->close();
}

?>