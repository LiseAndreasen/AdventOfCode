<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d05input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	$ranges_line = 1;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>0) {
			if($ranges_line == 1) {
				// next 4 lines changed in part 2
				// sort the ranges, lowest id first
				$ends = explode("-", $line);
				$ends_id = sprintf("%20d-%20d", $ends[0], $ends[1]);
				$data1[$ends_id] = $ends;
			} else {
				$data2[] = $line;
			}
		} else {
			$ranges_line = 2;
		}
	}
	return array($data1, $data2);
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$ranges = $data[0];
$ids = $data[1];
ksort($ranges); // added in part 2
//print_r($ranges);

$no_fresh = 0;
foreach($ids as $id) {
	foreach($ranges as $range) {
		if($range[0] <= $id && $id <= $range[1]) {
			$no_fresh++;
			continue 2;
		}
	}
}

printf("Result 1: %d\n", $no_fresh);

// concatenate ranges
foreach($ranges as $key1 => $range1) {
	if(!isset($ranges[$key1])) {
		// this range has already been concatenated elsewhere
		continue;
	}
	foreach($ranges as $key2 => $range2) {
		$a = $ranges[$key1][0];
		$b = $ranges[$key1][1];
		if($key2 <= $key1) {
			// skip duplictates
			continue;
		}
		$c = $range2[0];
		$d = $range2[1];
		// we know that a <= c
		if($b + 1 < $c) {
			// there's a gap between the ranges
			// and the rest of the ranges too
			continue 2;
		}
		if($d <= $b) {
			// all of range 2 is inside range 1
			unset($ranges[$key2]);
			continue;
		}
		// if we are here, the 2 ranges overlap
		$ranges[$key1][1] = $d;
		unset($ranges[$key2]);
	}
}

$no_pot_fresh = 0;
foreach($ranges as $range) {
	$no_pot_fresh += $range[1] - $range[0] + 1;
}

printf("Result 2: %d\n", $no_pot_fresh);

?>
