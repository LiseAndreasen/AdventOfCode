<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d18input1.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = explode(" ", $line);
		}
	}
	return $data;
}

function print_map($map, $min_x, $min_y) {
	$map_width = sizeof($map);
	$map_height = sizeof($map[0]);
	for($j=$min_y;$j<$min_y+$map_height;$j++) {
		for($i=$min_x;$i<$min_x+$map_width;$i++) {
			echo $map[$i][$j];
		}
		echo "\n";
	}
	for($i=0;$i<$map_width;$i++) {
		echo "=";
	}
	echo "\n";
}

function remake_map($min_x, $min_y) {
	global $map;
	$map2 = $map;
	$map_width = sizeof($map);
	$map_height = sizeof($map[0]);
	
	// assumption: this loop is nice
	/*
	| is a vertical pipe connecting north and south.
	- is a horizontal pipe connecting east and west.
	L is a 90-degree bend connecting north and east.
	J is a 90-degree bend connecting north and west.
	7 is a 90-degree bend connecting south and west.
	F is a 90-degree bend connecting south and east.
	*/	
	for($j=$min_y;$j<$min_y+$map_height;$j++) {
		for($i=$min_x;$i<$min_x+$map_width;$i++) {
			if($map[$i][$j] == "#") {
				// | ?
				if(isset($map[$i][$j-1]) && $map[$i][$j-1] == "#" && isset($map[$i][$j+1]) && $map[$i][$j+1] == "#") {
					$map2[$i][$j] = "|";
					continue;
				}
				// - ?
				if(isset($map[$i-1][$j]) && $map[$i-1][$j] == "#" && isset($map[$i+1][$j]) && $map[$i+1][$j] == "#") {
					$map2[$i][$j] = "-";
					continue;
				}
				// L ?
				if(isset($map[$i][$j-1]) && $map[$i][$j-1] == "#" && isset($map[$i+1][$j]) && $map[$i+1][$j] == "#") {
					$map2[$i][$j] = "L";
					continue;
				}
				// J ?
				if(isset($map[$i-1][$j]) && $map[$i-1][$j] == "#" && isset($map[$i][$j-1]) && $map[$i][$j-1] == "#") {
					$map2[$i][$j] = "J";
					continue;
				}
				// 7 ?
				if(isset($map[$i-1][$j]) && $map[$i-1][$j] == "#" && isset($map[$i][$j+1]) && $map[$i][$j+1] == "#") {
					$map2[$i][$j] = "7";
					continue;
				}
				// F ?
				if(isset($map[$i+1][$j]) && $map[$i+1][$j] == "#" && isset($map[$i][$j+1]) && $map[$i][$j+1] == "#") {
					$map2[$i][$j] = "F";
					continue;
				}
			}
		}
	}
	$map = $map2;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
//print_r($data);

// construct map of dig
$x = 0;
$y = 0;
$min_x = $x;
$max_x = $x;
$min_y = $y;
$max_y = $y;
$map[$x][$y] = "#";
foreach($data as $dig) {
	$dig_dir = $dig[0];
	$dig_lgt = $dig[1];
	for($i=0;$i<$dig_lgt;$i++) {
		switch($dig_dir) {
			case "L":
				$x--;
				break;
			case "R":
				$x++;
				break;
			case "U":
				$y--;
				break;
			case "D":
				$y++;
				break;
		}
		$map[$x][$y] = "#";
		if($x < $min_x) {
			$min_x = $x;
		}
		if($max_x < $x) {
			$max_x = $x;
		}
		if($y < $min_y) {
			$min_y = $y;
		}
		if($max_y < $y) {
			$max_y = $y;
		}
	}
}

// fill in map with empty bits
for($x=$min_x;$x<=$max_x;$x++) {
	for($y=$min_y;$y<=$max_y;$y++) {
		if(!isset($map[$x][$y])) {
			$map[$x][$y] = ".";
		}
	}
}

//print_map($map, $min_x, $min_y);
remake_map($min_x, $min_y);
//print_map($map, $min_x, $min_y);

// heavily copy from day 10

$map_width = sizeof($map);
$map_height = sizeof($map[0]);

// go through map, testing whether each tile is
// 1) not part of loop
// 2) goes through the loop an odd number of times coming from the left

for($j=$min_y;$j<$max_y;$j++) {
	$tiles_left = 0;
	for($i=$min_x;$i<$max_x;$i++) {
		if($map[$i][$j] != ".") {
			if($map[$i][$j] == "|") {
				$tiles_left++;
			} else {
				// the logic here is, that starting + ending above the line
				// gives a part of the loop, that can be ignored
				// similar for starting and ending below
				// this part starts above or ends below the line
				if($map[$i][$j] == "L" || $map[$i][$j] == "7") {
					$tiles_left += 0.5;
				} else {
					// the opposite
					if($map[$i][$j] == "F" || $map[$i][$j] == "J") {
						$tiles_left += - 0.5;
					}
				}
			}
		} else {
			if(abs($tiles_left) % 2 == 1) {
				$map[$i][$j] = "I";
			}
		}
	}
}
//print_map($map, $min_x, $min_y);

// count lava parts of map
$flat_map = array_merge(...$map);
$counts = array_count_values($flat_map);
//print_r($counts);

$all_map = sizeof($flat_map);
$lava_map = $all_map - $counts["."];

printf("Size of lava lagoon: %d\n", $lava_map);

?>
