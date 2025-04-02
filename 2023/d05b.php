<?php

// constants
$steps = array("seed", "soil", "fertilizer", "water", "light", "temperature", "humidity", "location");
$stepno = sizeof($steps);

// functions

//////////////////////////////////////////////

$input = file_get_contents('./d05input1.txt', true);

$step = -1;
$mapmode = 0;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	// seeds
	if(strlen($line)>2 && $step == -1 && preg_match('/seeds/', $line, $matches)) {
		// convert string with divided integers into array
		preg_match_all('/\d+/', $line, $seeds);
		$step++;
	}
	
	// maps
	// first line
	$str = $steps[$step] . "-to-" . $steps[$step+1];
	if(strlen($line)>2 && $step > -1 && preg_match("/$str/", $line, $matches)) {
		$mapmode = 1;
		continue;
	}
	// the maps
	if(strlen($line)>2 && $step > -1 && $mapmode == 1) {
		preg_match_all('/\d+/', $line, $maps[$step][]);
	}
	// no more maps
	if(strlen($line)<=2 && $step > -1 && $mapmode == 1) {
		$step++;
		$mapmode = 0;
	}
}

// convert each seed
$seedmode = 0;
foreach($seeds[0] as $seed) {
	// pair of numbers, the first is a seed number
	if($seedmode == 0) {
		$startseed = $seed;
		$seedmode = 1;
		print("First seed: $startseed, ");
		continue;
	}
	
	// the second number is a range
	
	$seedrange = $seed;
	print("no. of seeds: $seedrange\n");
	for($k=$startseed;$k<$startseed+$seedrange;$k++) {
		$thisseed = $k;
		if($thisseed % 1000000 == 0) {
			print(".");
		}
		//seed-to-soil map:
		//50 98 2
		// 1st number: soil
		// 2nd number: seed range, 1st number
		// 3rd number: range length
		// the seed range is 98-99, 2 numbers
		// the soil range is 50-51, 2 numbers
		
		// check every step for maps
		for($i=0;$i<$stepno-1;$i++) {
			// check every map
			$mapno = sizeof($maps[$i]);
			for($j=0;$j<$mapno;$j++) {
				$thismap = $maps[$i][$j][0];
				$dest = $thismap[0];
				$sour = $thismap[1];
				$rang = $thismap[2];
				if($sour <= $thisseed && $thisseed < $sour + $rang) {
					$thisseed = $thisseed - $sour + $dest;
					break;
				}
			}
		}
		$locs[] = $thisseed;
	}
	$seedmode = 0;
}

$lowest = min($locs);
print("Lowest location: $lowest\n");

?>
