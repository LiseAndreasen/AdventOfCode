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
			$data[] = str_split($line);
		}
	}
	return $data;
}

function find_marker($buffer, $length) {
    $i=0;
    $marker = array_slice($buffer, $i, $length);
    //print_r($marker);
    while(sizeof(array_unique($marker)) < $length) {
        $i++;
        $marker = array_slice($buffer, $i, $length);
    }
    return $i + $length;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$buffer = $data[0];

$i = find_marker($buffer, 4);
printf("Result 1: %d\n", $i);

$i = find_marker($buffer, 14);
printf("Result 2: %d\n", $i);

?>
