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
				$data1[] = str_split($line);
			} else {
				preg_match_all('/[\+\*]/', $line, $math);
				$data2 = str_split($line);
			}
		}
	}
	
	$data1 = pivot($data1);
	return array($data1, $data2);
}

// change (y, x) map to (x, y) map
function pivot($map) {
	$map_height = sizeof($map);
	$map_width = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			$map2[$i][$j] = $map[$j][$i];
		}
	}
	return $map2;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$numbers = $data[0];
$math = $data[1];

$number_sz = sizeof($numbers);
$number_sum = 0;
$number_prev = 0;
for($i=0;$i<$number_sz;$i++) {
	$new_no = implode("", $numbers[$i]);
	if($number_prev == 0) {
		$math_symbol = $math[$i];
		$number_prev = 1;
		$result = $new_no;
	} else {
		if(!is_numeric($new_no)) {
			// this is a blank line
			$number_prev = 0;
			$number_sum += $result;
		} else {
			if($math_symbol == "+") {
				$result += $new_no;
			} else {
				$result *= $new_no;
			}
		}
	}
}
// remember to add the last one
$number_sum += $result;

printf("Result 2: %d\n", $number_sum);

?>
