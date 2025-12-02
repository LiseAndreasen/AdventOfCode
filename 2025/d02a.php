<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d02input1.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			// convert csv to array
			$data1 = explode(",", $line);
			foreach($data1 as $range) {
				$data2[] = explode("-", $range);
			}
		}
	}
	return $data2;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$invalid_id_sum = 0;
foreach($data as $range) {
	$beg = $range[0];
	$end = $range[1];
	for($i=$beg;$i<=$end;$i++) {
		$digits = strlen((string) $i);
		if($digits % 2 == 1) {
			continue;
		}
		$border = pow(10, $digits/2);
		$front = floor($i / $border);
		$back = $i % $border;
		if($front == $back) {
			$invalid_id_sum += $i;
		}
	}
}

printf("Result 1: %d\n", $invalid_id_sum);

?>
