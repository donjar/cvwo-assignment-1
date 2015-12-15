<?php

$hostname = "localhost";
$username = "root";
$password = "rootpassword";

$mysqli_obj = @new mysqli($hostname, $username, $password);

if ($mysqli_obj->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli_obj->connect_errno .
		") " . $mysqli_obj->connect_error;
} else {
	echo "Connected! <br />";

	$stmt = "DROP DATABASE IF EXISTS test;";
	$stmt .= "CREATE DATABASE test;";
	if ($mysqli_obj->multi_query($stmt)) {
		echo $stmt . " successfully run!";
	} else {
		echo $stmt . "<br />" . $mysqli_obj->error;
	}
	
	$mysqli_obj->close();
}

?>