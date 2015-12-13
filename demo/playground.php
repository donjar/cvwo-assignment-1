<?php

$an_array = [0 => "foo", true, 6 => 2.5, 3, [2, 3, 5]];
for ($index = 0; $index < count($an_array) - 3; $index++) {
	var_dump($an_array[$index]);
	echo "<br/>";
}

echo "<br/>";

foreach ($an_array as $key => $value) {
	var_dump($value);
	echo ($key * $key);
	echo "<br/>";
}