<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d12input1n.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			// 1st check
			// is the region big enough?
			// only keep the last lines
			if(strpos($line, "x") !== false) {
				# 4x4: 0 0 0 0 2 0
				$data1 = explode(": ", $line);
				$data2 = explode("x", $data1[0]);
				$data3 = explode(" ", $data1[1]);
				$data[] = array($data2, $data3);
			}
		}
	}
	return $data;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$yes_no = 0; // yes number...
$no_no = 0; // no number...
$maybe = 0;
foreach($data as $region) {
	// how many 1x1 blocks in the region?
	$region_sz1 = $region[0][0] * $region[0][1];
	// how many 3x3 blocks in the region?
	$region_sz3 = floor($region[0][0] / 3) * floor($region[0][1] / 3);
	// how many presents in the region?
	$present_sz = array_sum($region[1]);
	if($region_sz1 < $present_sz * 7) {
		// there's absolutely not enough room for all the presents
		$no_no++;
	} else {
		if($present_sz <= $region_sz3) {
			// there's absolutely enough room for all the presents
			$yes_no++;
		} else {
			$maybe++;
		}
	}
}

printf("Result 1: Yes %d Maybe %d No %d\n", $yes_no, $maybe, $no_no);

// that worked???

?>
