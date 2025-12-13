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
foreach($data as $region) {
	$region_sz = floor($region[0][0] / 3) * floor($region[0][1] / 3);
	$present_sz = array_sum($region[1]);
	//printf("Region %d x %d. Presents %d. ",
	//	$region[0][0], $region[0][1], $present_sz);
	//printf("Region (%d) < Presents (%d)? ", $region_sz, $present_sz);
	if($region_sz < $present_sz) {
		//print("Oh no!\n");
	} else {
		//print("Oh yes!\n");
		$yes_no++;
	}
}

printf("Result 1: %d\n", $yes_no);

// that worked???

?>
