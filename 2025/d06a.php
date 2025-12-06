<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d06input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			preg_match_all('/\d+/', $line, $no);
			if(0 < sizeof($no[0])) {
				$data1[] = $no[0];
			} else {
				preg_match_all('/[\+\*]/', $line, $math);
				$data2 = $math[0];
			}
		}
	}
	return array($data1, $data2);
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$numbers = $data[0];
$math = $data[1];

$no_of_math = sizeof($numbers[0]);

$math_sum = 0;
for($i=0;$i<$no_of_math;$i++) {
	$math_symbol = $math[$i];
	// assuming all numbers are positive
	$math_result = -1;
	foreach($numbers as $number) {
		if($math_result == -1) {
			$math_result = $number[$i];
		} else {
			if($math_symbol == "+") {
				$math_result += $number[$i];
			} else {
				$math_result *= $number[$i];
			}
		}
	}
	$math_sum += $math_result;
}

printf("Result 1: %d\n", $math_sum);

?>
