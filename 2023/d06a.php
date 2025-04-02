<?php

//////////////////////////////////////////////

$input = file_get_contents('./d06input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		// convert string with divided integers into array
		preg_match_all('/\d+/', $line, $data);
		$datas[] = $data[0];
	}
}

// Your toy boat has a starting speed of zero millimeters per millisecond. For
// each whole millisecond you spend at the beginning of the race holding down
// the button, the boat's speed increases by one millimeter per millisecond.

$times = $datas[0];
$dists = $datas[1];

// product of hits
$hitsprod = 1;

// for each race
foreach($times as $id => $time) {
	// $time already defined
	$dist = $dists[$id];
	// no of times it could have been done faster
	$hits = 0;
	
	// for each possible hold time
	for($i=0;$i<=$time;$i++) {
		// hold time: $i
		// travel time: $time - $i
		$newdist = $i * ($time - $i);
		if($newdist > $dist) {
			//print("Hit! Time: $time. Distance: $dist. Hold time: $i. New distance: $newdist.\n");
			$hits++;
		}
	}
	
	$hitsprod *= $hits;
}

print("Product of hits: $hitsprod\n");

?>
