<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d15input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

/*
The HASH algorithm is a way to turn any string of characters into a
single number in the range 0 to 255. To run the HASH algorithm on a string,
start with a current value of 0. Then, for each character in the string
starting from the beginning:

    Determine the ASCII code for the current character of the string.
    Increase the current value by the ASCII code you just determined.
    Set the current value to itself multiplied by 17.
    Set the current value to the remainder of dividing itself by 256.

*/

function hashing($hash) {
	$hash_lgt = strlen($hash);
	$hash_val = 0;
	for($i=0;$i<$hash_lgt;$i++) {
		$hash_this = ord($hash[$i]);
		$hash_val += $hash_this;
		$hash_val *= 17;
		$hash_val = $hash_val % 256;
	}
	return $hash_val;
}

///////////////////////////////////////////////////////////////////////////
// main program

// absorb input file, line by line
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$hash_line = $line;
	}
}
$hashes = explode(",", $hash_line);

$hash_sum = 0;
foreach($hashes as $hash) {
	$hash_val = hashing($hash);
	$hash_sum += $hash_val;
}

printf("Hash sum: %d\n", $hash_sum);

// part 2

$boxes = array();

foreach($hashes as $hash) {
	// extract label, operation and focal length
	$hash_lgt = strlen($hash);
	$hash_label = substr($hash, 0, -2);
	$hash_op = substr($hash, -2, 1);
	$hash_foc = substr($hash, -1);

	// beware, if focal length isn't a number, shuffle
	if(!is_numeric($hash_foc)) {
		$hash_label .= $hash_op;
		$hash_op = $hash_foc;
		$hash_foc = 0;
	}

	$hash_box_no = hashing($hash_label);
	
	// remove?
	if($hash_op == "-") {
		if(isset($boxes[$hash_box_no])) {
			foreach($boxes[$hash_box_no] as $key => $lens) {
				$lens_label = explode(" ", $lens);
				if(strcmp($lens_label[0], $hash_label) == 0) {
					unset($boxes[$hash_box_no][$key]);
					break;
				}
			}
		}
	}

	// add?
	if($hash_op == "=") {
		$lens_replaced = 0;
		if(isset($boxes[$hash_box_no])) {
			foreach($boxes[$hash_box_no] as $key => $lens) {
				$lens_label = explode(" ", $lens);
				if(strcmp($lens_label[0], $hash_label) == 0) {
					$boxes[$hash_box_no][$key] = "$hash_label $hash_foc";
					$lens_replaced = 1;
					break;
				}
			}
		}
		if($lens_replaced == 0) {
			if(!isset($boxes[$hash_box_no])) {
				$boxes[$hash_box_no] = array();
			}
			$boxes[$hash_box_no][] = "$hash_label $hash_foc";
		}
	}
}

foreach($boxes as $key1 => $box) {
	// reorder to skip empty spaces
	$boxes[$key1] = array_values($boxes[$key1]);
}

$power_sum = 0;
foreach($boxes as $key1 => $box) {
	foreach($box as $key2 => $lens) {
		// example:
		// rn: 1 (box 0) * 1 (first slot) * 1 (focal length) = 1
		$lens_foc = substr($lens, -1);
		$power_sum += ($key1 + 1) * ($key2 + 1) * $lens_foc;
	}
}

printf("Power sum: %d\n", $power_sum);

?>
