<?php

///////////////////////////////////////////////////////////////////////////
// constants

// test: 1, real: 2
$input = file_get_contents('./d11input2.txt', true);

// how much distance does a * cover?
// part 1: 2, part 2: 1000000
$star_dist = 1000000;

///////////////////////////////////////////////////////////////////////////
// functions

// change (y, x) map to (x, y) map
function pivot($map) {
	$map_height = sizeof($map);
	$map_width = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			$map2[$i][$j] = $map[$j][$i];
		}
	}
	return $map2;
}

function print_map($map) {
	$map_width = sizeof($map);
	$map_height = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			echo $map[$i][$j];
		}
		echo "\n";
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

// absorb input file, line by line
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	//print("$line\n");
	if(strlen($line)>2) {
		$map[] = str_split($line);
	}
}

// empty rows and columns should be doubled
$map_height = sizeof($map);
$map_width = sizeof($map[0]);
// initialize empty line
for($i=0;$i<$map_width;$i++) {
	$empty_line[] = "*";
}
for($i=0;$i<$map_height;$i++) {
	// if line is empty, double
	$counts = array_count_values($map[$i]);
	if(!isset($counts["#"])) {
		$map2[] = $empty_line;
	} else {
		$map2[] = $map[$i];
	}
}
$map = pivot($map2);

$map2 = array();
$empty_line = array();

$map_height = sizeof($map);
$map_width = sizeof($map[0]);
// initialize empty line
for($i=0;$i<$map_width;$i++) {
	$empty_line[] = "*";
}
for($i=0;$i<$map_height;$i++) {
	// if line is empty, double
	$counts = array_count_values($map[$i]);
	if(!isset($counts["#"])) {
		$map2[] = $empty_line;
	} else {
		$map2[] = $map[$i];
	}
}
$map = $map2;

// find all galaxies
// go through all parts of map
for($i=0;$i<sizeof($map);$i++) {
	for($j=0;$j<sizeof($map[$i]);$j++) {
		if($map[$i][$j] == "#") {
			$galax[] = array($i, $j);
		}
	}
}

// calculate distances
$dist_sum = 0;
$galax_num = sizeof($galax);
for($i=0;$i<$galax_num-1;$i++) {
	for($j=$i+1;$j<$galax_num;$j++) {
		$dist = 0;
		// horizontal distance
		// vertical distance
		$x_begin = min($galax[$i][0],$galax[$j][0]);
		$y_begin = min($galax[$i][1],$galax[$j][1]);
		$x_end = max($galax[$i][0],$galax[$j][0]);
		$y_end = max($galax[$i][1],$galax[$j][1]);
		for($k=$x_begin+1;$k<=$x_end;$k++) {
			if($map[$k][0] == "*") {
				$dist += $star_dist;
			} else {
				$dist++;
			}
		}
		for($k=$y_begin+1;$k<=$y_end;$k++) {
			if($map[0][$k] == "*") {
				$dist += $star_dist;
			} else {
				$dist++;
			}
		}
		$dist_sum += $dist;
	}
}

print("Sum of distances: $dist_sum\n");

?>
