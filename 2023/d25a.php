<?php

include "stoerwagner.php";

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d25input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			// cmg: qnr nvd lhk bvb
			$data1 = explode(": ", $line);
			$data2 = explode(" ", $data1[1]);
			$data[$data1[0]] = $data2;
		}
	}
	return $data;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$G = [];

// make list of connections
// make subgraphs, at first with only 1 element
foreach($data as $name1 => $list) {
	foreach($list as $name2) {
	    add_edge($G, $name1, $name2, 1);
	}
}

[$cut_value, $partition] = stoer_wagner($G);
print("\ncut value " . $cut_value . "\n");
//print_r($partition);
$l0 = sizeof($partition[0]);
$l1 = sizeof($partition[1]);
printf("Sizes of partitions multiplied: %d x %d = %d\n", $l0, $l1, $l0 * $l1);

//printf("Result 1: %d\n", $num);

?>
