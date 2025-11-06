<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d16input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$map[] = str_split($line);
		}
	}
	return $map;
}

// change (y, x) map to (x, y) map
function pivot($map) {
	$map_height = sizeof($map);
	$map_width = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			$map2[$j][$i] = $map[$i][$j];
		}
	}
	return $map2;
}

function print_map($map) {
	$map_height = sizeof($map);
	$map_width = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			echo $map[$i][$j];
		}
		echo "\n";
	}
	print("======================\n");
}

// follow beam until it goes off the map
// function begins having just arrived on a new tile
function beam_moves($map, $begin, $dir) {
	global $map_e, $map_v;
	
	// height, width
	$w = sizeof($map);
	$h = sizeof($map[0]);
	
	$x = $begin[0];
	$y = $begin[1];
	
	// have we been here before?
	if(isset($map_v[$x][$y][$dir])) {
		return;
	}
	
	// are we still on the map?
	if($x == -1 || $x == $w) {
		return;
	}
	if($y == -1 || $y == $h) {
		return;
	}
	// register out presence
	$map_v[$x][$y][$dir] = 1;

	// mark tile
	$map_e[$x][$y] = "#";
	
	// possible values: . / \ | -
	$this_tile = $map[$x][$y];
	
	// possible directions: right, left, down, up
	
	if($this_tile == ".") {
		// just keep going
		switch($dir) {
			case "r":
				beam_moves($map, array($x+1, $y), $dir);
				break;
			case "l":
				beam_moves($map, array($x-1, $y), $dir);
				break;
			case "d":
				beam_moves($map, array($x, $y+1), $dir);
				break;
			case "u":
				beam_moves($map, array($x, $y-1), $dir);
				break;
		}
	}
	
	if($this_tile == '/' || $this_tile == '\\') {
		// turn 90 deg
		switch($dir) {
			case "r":
				if($this_tile == "/") {
					beam_moves($map, array($x, $y-1), "u");
				} else {
					beam_moves($map, array($x, $y+1), "d");
				}
				break;
			case "l":
				if($this_tile == "/") {
					beam_moves($map, array($x, $y+1), "d");
				} else {
					beam_moves($map, array($x, $y-1), "u");
				}
				break;
			case "d":
				if($this_tile == "/") {
					beam_moves($map, array($x-1, $y), "l");
				} else {
					beam_moves($map, array($x+1, $y), "r");
				}
				break;
			case "u":
				if($this_tile == "/") {
					beam_moves($map, array($x+1, $y), "r");
				} else {
					beam_moves($map, array($x-1, $y), "l");
				}
				break;
		}
	}

	if($this_tile == "|" || $this_tile == "-") {
		// turn 90 deg, both directions
		// or simply pass through
		switch($dir) {
			case "r":
				if($this_tile == "-") {
					beam_moves($map, array($x+1, $y), $dir);
				} else {
					beam_moves($map, array($x, $y-1), "u");
					beam_moves($map, array($x, $y+1), "d");
				}
				break;
			case "l":
				if($this_tile == "-") {
					beam_moves($map, array($x-1, $y), $dir);
				} else {
					beam_moves($map, array($x, $y-1), "u");
					beam_moves($map, array($x, $y+1), "d");
				}
				break;
			case "d":
				if($this_tile == "|") {
					beam_moves($map, array($x, $y+1), $dir);
				} else {
					beam_moves($map, array($x-1, $y), "l");
					beam_moves($map, array($x+1, $y), "r");
				}
				break;
			case "u":
				if($this_tile == "|") {
					beam_moves($map, array($x, $y-1), $dir);
				} else {
					beam_moves($map, array($x-1, $y), "l");
					beam_moves($map, array($x+1, $y), "r");
				}
				break;
		}
	}
	
}

///////////////////////////////////////////////////////////////////////////
// main program

$map = get_input($input);
$map = pivot($map);

// make a copy of the map, only registering energized tiles - initialize
// also make a copy of the map to register
// whether this tile has been visited before going in this direction
// go through all parts of map
for($i=0;$i<sizeof($map);$i++) {
	$map_e[$i] = array();
	$map_v[$i] = array();
	for($j=0;$j<sizeof($map[$i]);$j++) {
		$map_e[$i][$j] = ".";
		$map_v[$i][$j] = array();
	}
}

// beam starts top left moving right
$begin = array(0,0);
$dir = "r";

// send the beam
beam_moves($map, $begin, $dir);

// count energized tiles on map
$energy = array_merge(...$map_e);
$counts = array_count_values($energy);
printf("Energized tiles: %d\n", $counts["#"]);

?>
