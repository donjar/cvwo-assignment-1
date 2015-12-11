<?php

function sumup ($upper) {
	$sum = 0;
	for ($i = 1; $i <= $upper; $i++){
		$sum += $i;
	}
	return $sum;
}

?>