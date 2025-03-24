<?php

//////////////////////////////////////////////

// get input from file
$input = file_get_contents('./d01input3.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	// all real lines have at least 1 character
	if(strlen($line)>1) {
		// "replace" these with digits:
		// one, two, three, four, five, six, seven, eight, and nine
		// it has to be from the end:
		// twone is two from the left, one from the right
		
		$nums = array("xxxxx", "one", "two", "three", "four", "five",
			"six", "seven", "eight", "nine");
		// the first is a dummy value, xxxxx doesn't occur in the data
		$line2 = $line;
		foreach($nums as $dig => $num) {
			// https://stackoverflow.com/questions/15737408/
			// php-find-all-occurrences-of-a-substring-in-a-string
			$lastPos = 0;

			while (($lastPos = strpos($line, $num, $lastPos))!== false) {
				// if a num is found in line,
				// in line2 the first letter is replaced with the digit
				// that way two is found correctly in line
				// even though one was already found and noted in line2
				$line2[$lastPos] = $dig;
				$lastPos = $lastPos + strlen($num);
			}			
		}
		
		// throw away all non digits
		$lines[] = preg_replace("/[a-zA-Z]/", "", $line2);
		
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
