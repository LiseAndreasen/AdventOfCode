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

for($i=0;$i<$szall;$i++) { // rows
  for($j=0;$j<$szall;$j++) { // columns 
	// find an X
	if($all[$i][$j] == "X") {
		//print("$i $j\n");
	} else {
		continue;
	}
	
	// examine N
	if($i >= 3) {
		if($all[$i-1][$j] == "M" 
		&& $all[$i-2][$j] == "A"
		&& $all[$i-3][$j] == "S") {
			//print("hit $i $j\n");
			$cnt++;
		}
	}
	// examine NE
	if($i >= 3 && $j <= $szall - 4) {
		if($all[$i-1][$j+1] == "M" 
		&& $all[$i-2][$j+2] == "A"
		&& $all[$i-3][$j+3] == "S") {
			$cnt++;
		}
	}
	// examine E
	if($j <= $szall - 4) {
		if($all[$i][$j+1] == "M" 
		&& $all[$i][$j+2] == "A"
		&& $all[$i][$j+3] == "S") {
			$cnt++;
		}
	}
	// examine SE
	if($i <= $szall - 4 && $j <= $szall - 4) {
		if($all[$i+1][$j+1] == "M" 
		&& $all[$i+2][$j+2] == "A"
		&& $all[$i+3][$j+3] == "S") {
			$cnt++;
		}
	}
	// examine S
	if($i <= $szall - 4) {
		if($all[$i+1][$j] == "M" 
		&& $all[$i+2][$j] == "A"
		&& $all[$i+3][$j] == "S") {
			$cnt++;
		}
	}
	// examine SW
	if($i <= $szall - 4 && $j >= 3) {
		if($all[$i+1][$j-1] == "M" 
		&& $all[$i+2][$j-2] == "A"
		&& $all[$i+3][$j-3] == "S") {
			$cnt++;
		}
	}
	// examine W
	if($j >= 3) {
		if($all[$i][$j-1] == "M" 
		&& $all[$i][$j-2] == "A"
		&& $all[$i][$j-3] == "S") {
			$cnt++;
		}
	}
	// examine NW
	if($i >= 3 && $j >= 3) {
		if($all[$i-1][$j-1] == "M" 
		&& $all[$i-2][$j-2] == "A"
		&& $all[$i-3][$j-3] == "S") {
			$cnt++;
		}
	}
	
  }
}

echo $cnt . "\n";

?>
