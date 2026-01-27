<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d23input2.txt', true);

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
	foreach($map[0] as $j => $cell) {
		foreach($map as $i => $col) {
			echo $map[$i][$j];
		}
		echo "\n";
	}
	for($i=0;$i<sizeof($map);$i++) {
		echo "=";
	}
	echo "\n";
}

function walk_path($map, $here, $end, $l) {
	global $max_walk;
	
	// already walked tiles will be marked
	[$x0, $y0] = $here;
	$map[$x0][$y0] = "O";
	
	if($x0 == $end[0] && $y0 == $end[1]) {
		// printf("Path walked, length %d\n", $l);
		if($max_walk < $l) {
			$max_walk = $l;
		}
		return 1;
	}

	if(isset($map[$x0-1][$y0])) {
		$poss = $map[$x0-1][$y0];
		if($poss != "#") {
			if($poss == "." || $poss == "<") {
				walk_path($map, [$x0-1, $y0], $end, $l+1);
			}
		}
	}

	if(isset($map[$x0+1][$y0])) {
		$poss = $map[$x0+1][$y0];
		if($poss != "#") {
			if($poss == "." || $poss == ">") {
				walk_path($map, [$x0+1, $y0], $end, $l+1);
			}
		}
	}

	if(isset($map[$x0][$y0-1])) {
		$poss = $map[$x0][$y0-1];
		if($poss != "#") {
			if($poss == "." || $poss == "^") {
				walk_path($map, [$x0, $y0-1], $end, $l+1);
			}
		}
	}

	if(isset($map[$x0][$y0+1])) {
		$poss = $map[$x0][$y0+1];
		if($poss != "#") {
			if($poss == "." || $poss == "v") {
				walk_path($map, [$x0, $y0+1], $end, $l+1);
			}
		}
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = pivot(get_input($input));
// width, start, end
$w = sizeof($data);
$s = [1, 0];
$e = [$w - 2, $w - 1];
// max_walk will change
$max_walk = 0;
walk_path($data, $s, $e, 0);

printf("Result 1: %d\n", $max_walk);

?>
