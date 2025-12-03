<?php

$input1 = "MMMSXXMASM
MSAMXMSMSA
AMXSXMAAMM
MSAMASMSMX
XMASAMXAMM
XXAMMXXAMA
SMSMSASXSS
SAXAMASAAA
MAMMMXMMMM
MXMXAXMASX";

$all = array();

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input1) as $line){
  // do stuff with $line
  $all[] = str_split($line);
}

$cnt = 0;

// in both inputs it's a square of letters
$szall = sizeof($all);

for($i=1;$i<$szall-1;$i++) { // rows
  for($j=1;$j<$szall-1;$j++) { // columns 
    // note that A can't be on any edge
	// find an A
	if($all[$i][$j] != "A") {
		continue;
	}
	
	$good = 0;
	// check going right and down
	if($all[$i-1][$j-1] == "M" && $all[$i+1][$j+1] == "S") {
		$good++;
	}
	// check going left and up
	if($all[$i-1][$j-1] == "S" && $all[$i+1][$j+1] == "M") {
		$good++;
	}
	if($good == 0) {
		continue;
	}
	// check going right and up
	if($all[$i+1][$j-1] == "M" && $all[$i-1][$j+1] == "S") {
		$good++;
	}
	// check going left and down
	if($all[$i+1][$j-1] == "S" && $all[$i-1][$j+1] == "M") {
		$good++;
	}
	if($good == 2) {
		$cnt++;
	}
  }
}

echo $cnt . "\n";

?>
