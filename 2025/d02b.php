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

$invalid_id_sum_2 = 0;
$invalid_id_sum_n = 0;
foreach($data as $range) {
	$beg = $range[0];
	$end = $range[1];
	for($i=$beg;$i<=$end;$i++) {
		// no of digits in number:
		$digits = strlen((string) $i);
		// no of digits in repeated sequence:
		for($j=1;$j<=12;$j++) {
			if($digits % $j != 0) {
				continue;
			}
			// there must be at least 2 repeats
			if($digits == $j) {
				continue;
			}
			// find the last repeated sequence (maybe)
			$border = pow(10, $j);
			$back = $i % $border;
			// create number with back repeated
			$all_of_it = $back;
			for($k=2;$k<=$digits/$j;$k++) {
				$all_of_it .= $back;
			}
			if($i == $all_of_it) {
				$invalid_id_sum_n += $i;
				if($digits / $j == 2) {
					$invalid_id_sum_2 += $i;
				}
				// only register this i once
				continue 2;
			}
		}
	}
}

printf("Result 2: %d\n", $invalid_id_sum_n);

?>
