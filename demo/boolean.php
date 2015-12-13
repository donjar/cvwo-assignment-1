<?php

$foo = true;
$a_certain_number = 8;

function factorial($num) {
	return ($num === 0) ? 1 : ($num * factorial($num - 1));
}

if ($foo) {
	echo factorial($a_certain_number);
} else {
	echo 0;
}