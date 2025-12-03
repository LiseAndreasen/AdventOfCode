<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d03input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = $line;
		}
	}
	return $data;
}

function find_best($line, $size) {
	if($size == 0) {
		// we don't need anymore digits
		return ".0";
	}
	
	// find the largest digit
	// among those digits that are not the last size - 1
	
	$line_lgt = strlen($line);
	// cut off the last size - 1 digits
	$allowed_digits = substr($line, 0, $line_lgt - $size + 1);
	
	// find largest digit among those remaining
	$large_digit = max(str_split($allowed_digits));
	
	// find position of this digit
	$digit_pos = strpos($allowed_digits, $large_digit);
	
	// find the rest of the digits
	$remaining_line = substr($line, $digit_pos + 1);
	$remaining_digits = find_best($remaining_line, $size - 1);
	
	return $large_digit . $remaining_digits;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$combo_sum = 0;
foreach($data as $line) {
	$result = find_best($line, 2);
	$combo_sum += $result;
}

printf("Result 1: %d\n", $combo_sum);

$combo_sum = 0;
foreach($data as $line) {
	$result = find_best($line, 12);
	$combo_sum += $result;
}

printf("Result 2: %d\n", $combo_sum);

?>
