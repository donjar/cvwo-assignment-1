<table>
<tr>
<th>Number</th>
<th>Square mod 13</th>
<th>Cube mod 17</th>
</tr>
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

	$select_stmt = "SELECT num, square % 13, cube % 17 FROM number_table;";
	
	if ($select_run = $mysqli_obj->prepare($select_stmt)) {
		$select_run->execute();
		$select_run->bind_result($num, $sq, $cube);

		while ($select_run->fetch()) {
			echo "<tr><td>" . $num . "</td><td>" . $sq . "</td><td>" .
			$cube . "</td></tr>";
		}
	}
	
	$select_run->close();
	$mysqli_obj->close();
}

?>