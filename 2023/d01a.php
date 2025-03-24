<?php

//////////////////////////////////////////////

// get input from file
$input = file_get_contents('./d01input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	// all real lines have at least 1 character
	if(strlen($line)>1) {
		// throw away all non digits
		$lines[] = preg_replace("/[a-zA-Z]/", "", $line);
		
	}
}

// calculate each number
foreach($lines as $line) {
	$ll = strlen($line);
	$first = $line[0];
	$last = $line[$ll - 1];
	$res[] = $first . $last;
}

// calculate and show sum
print(array_sum($res))."\n";

?>
