<?php

// constants

$input = file_get_contents('./d10input3.txt', true);

// functions

// https://www.vacilando.org/article/php-implementation-tarjans-cycle-detection-algorithm
function php_tarjan_entry($G_local){

	// All the following must be global to pass them to recursive function php_tarjan().
	global $cycles;
	global $marked;
	global $marked_stack;
	global $point_stack;

	// Initialize global values that are so far undefined.
	$cycles = array();
	$marked = array();
	$marked_stack = array();
	$point_stack = array();

	for ($x = 0; $x < count($G_local); $x++) {
		$marked[$x] = FALSE;
	}

	for ($i = 0; $i < count($G_local); $i++) {
		php_tarjan($i, $i);
		while (!empty($marked_stack)) {
			$marked[array_pop($marked_stack)] = FALSE;
		}
		//echo '<br>'.($i+1).' / '.count($G_local); // Enable if you wish to follow progression through the array rows.
	}

	$cycles = array_keys($cycles);

	return $cycles;
}

/*
 * Recursive function to detect strongly connected components (cycles, loops).
 */
function php_tarjan($s, $v){

	// Source node-adjacency array.
	global $G;

	// All the following must be global to pass them to recursive function php_tarjan().
	global $cycles;
	global $marked;
	global $marked_stack;
	global $point_stack;

	$f = FALSE;
	$point_stack[] = $v;
	$marked[$v] = TRUE;
	$marked_stack[] = $v;

	//$maxlooplength = 3; // Enable to Limit the length of loops to keep in the results (see below).

	foreach($G[$v] as $w) {
		if ($w < $s) {
			$G[$w] = array();
		} else if ($w == $s) {
			//if (count($point_stack) == $maxlooplength){ // Enable to collect cycles of a given length only.
				// Add new cycles as array keys to avoid duplication. Way faster than using array_search.
				$cycles[implode('|', $point_stack)] = TRUE;
			//}
			$f = TRUE;
		} else if ($marked[$w] === FALSE) {
			//if (count($point_stack) < $maxlooplength){ // Enable to only collect cycles up to $maxlooplength.
				$g = php_tarjan($s, $w);
			//}
			if (!empty($f) OR !empty($g)){
				$f = TRUE;
			}
		}
	}

	if ($f === TRUE) {
		while (end($marked_stack) != $v){
			$marked[array_pop($marked_stack)] = FALSE;
		}
		array_pop($marked_stack);
		$marked[$v] = FALSE;
	}

	array_pop($point_stack);
	return $f;
}

// change (y, x) map to (x, y) map
function pivot() {
	global $map, $map_width, $map_height;
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			$map2[$i][$j] = $map[$j][$i];
		}
	}
	$map = $map2;
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
	echo "\n";
}

//////////////////////////////////////////////

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	//print("$line\n");
	if(strlen($line)>2) {
		$map[] = str_split($line);
	}
}

$map_height = sizeof($map);
$map_width = sizeof($map[0]);
pivot();

// find S
for($i=0;$i<$map_width;$i++) {
	$j = array_search("S", $map[$i]);
	if($j !== false) {
		$S_x = $i;
		$S_y = $j;
	}
}
// find number for S
$S_pos = $S_x * $map_height + $S_y;

/*
| is a vertical pipe connecting north and south.
- is a horizontal pipe connecting east and west.
L is a 90-degree bend connecting north and east.
J is a 90-degree bend connecting north and west.
7 is a 90-degree bend connecting south and west.
F is a 90-degree bend connecting south and east.
. is ground; there is no pipe in this tile.
S is the starting position of the animal;
	there is a pipe on this tile, but your
	sketch doesn't show what shape the pipe has.
*/

// create graph
/*
 * Array is to contain the graph in node-adjacency format.
 */

// initialize 2d array of connections
for($i=0;$i<$map_width*$map_height;$i++) {
	$graph[$i] = array();
}

// look from current position to the right and below
// positions enumerated:
// (0,0) -> 0
// (0,1) -> 1
// (1,0) -> mh
// (x,y) -> x * mh + y
for($x=0;$x<$map_width;$x++) {
	for($y=0;$y<$map_height;$y++) {
		$my_pos = $x * $map_height + $y;
		$right_pos = ($x + 1) * $map_height + $y;
		$below_pos = $x * $map_height + $y + 1;
		// connection to the left?
		if($x <= $map_width - 2) {
			if(($map[$x][$y] == "S" || $map[$x][$y] == "-"
			|| $map[$x][$y] == "L" || $map[$x][$y] == "F") &&
			($map[$x+1][$y] == "S" || $map[$x+1][$y] == "-"
			|| $map[$x+1][$y] == "J" || $map[$x+1][$y] == "7")) {
				$graph[$my_pos][] = $right_pos;
				$graph[$right_pos][] = $my_pos;
			}
		}
		// connection below?
		if($y <= $map_height - 2) {
			if(($map[$x][$y] == "S" || $map[$x][$y] == "|"
			|| $map[$x][$y] == "7" || $map[$x][$y] == "F") &&
			($map[$x][$y+1] == "S" || $map[$x][$y+1] == "|"
			|| $map[$x][$y+1] == "L" || $map[$x][$y+1] == "J")) {
				$graph[$my_pos][] = $below_pos;
				$graph[$below_pos][] = $my_pos;
			}
		}
	}
}

// find loops

$G = $graph;
$cycles = php_tarjan_entry($G);

// the loop we're looking for should have S
// and should have more than 2 elements
foreach($cycles as $cycle) {
	// convert string with divider into array
	$cycle_arr = explode("|", $cycle);
	if(in_array($S_pos, $cycle_arr) && sizeof($cycle_arr) > 2) {
		$big_cycle = $cycle_arr;
	}
}

// calculate distances
// the point the farthest from S is size_of_loop/2 tiles away

$counting_pipe = sizeof($big_cycle);
$dist = $counting_pipe / 2;

printf("Distance to farthest point: %d\n\n", $dist);

// counting tiles within the loop
// a tile enclosed by the loop has to cross the loop an odd number of times
// to reach any edge

// change all tiles not part of loop, into . on map
$map2 = $map;
for($j=0;$j<$map_height;$j++) {
	for($i=0;$i<$map_width;$i++) {
		$tmp = $i * $map_height + $j;
		if(! in_array($tmp, $big_cycle)) {
			$map2[$i][$j] = ".";
		}
	}
}

// go through map, testing whether each tile is
// 1) not part of loop
// 2) goes through the loop an odd number of times coming from the left

for($j=0;$j<$map_height;$j++) {
	$tiles_left = 0;
	for($i=0;$i<$map_width;$i++) {
		if($map2[$i][$j] != ".") {
			if($map2[$i][$j] == "|") {
				$tiles_left++;
			} else {
				// the logic here is, that starting + ending above the line
				// gives a part of the loop, that can be ignored
				// similar for starting and ending below
				// this part starts above or ends below the line
				if($map2[$i][$j] == "L" || $map2[$i][$j] == "7") {
					$tiles_left += 0.5;
				} else {
					// the opposite
					if($map2[$i][$j] == "F" || $map2[$i][$j] == "J") {
						$tiles_left += - 0.5;
					}
				}
			}
		} else {
			if(abs($tiles_left) % 2 == 1) {
				$map2[$i][$j] = "I";
			}
		}
	}
}

// flatten map, count I's
$map3 = array_merge(...$map2);
$counts = array_count_values($map3);
if(isset($counts["I"])) {
	printf("Number of tiles within loop: %d\n", $counts["I"]);
} else {
	printf("Number of tiles within loop: 0\n");
}

?>
