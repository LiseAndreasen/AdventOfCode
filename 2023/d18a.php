<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d18input2.txt', true);
$part = 2;

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

// which character represents this corner of the lagoon?
function corner_char($old_dir, $dig_dir) {
	if($old_dir == "U" && $dig_dir == "L") {
		return "7";
	}
	if($old_dir == "U" && $dig_dir == "R") {
		return "F";
	}
	if($old_dir == "D" && $dig_dir == "L") {
		return "J";
	}
	if($old_dir == "D" && $dig_dir == "R") {
		return "L";
	}
	if($old_dir == "L" && $dig_dir == "U") {
		return "L";
	}
	if($old_dir == "L" && $dig_dir == "D") {
		return "F";
	}
	if($old_dir == "R" && $dig_dir == "U") {
		return "J";
	}
	if($old_dir == "R" && $dig_dir == "D") {
		return "7";
	}
}

// given a map, mark the internal parts of it
function fill_in_map(&$map, $data_x, $data_y) {
	// fill in map with empty bits
	foreach($data_y as $y) {
		foreach($data_x as $x) {
			if(!isset($map[$x][$y])) {
				$map[$x][$y] = ".";
			}
		}
	}

	foreach($data_y as $j) {
		$tiles_left = 0;
		foreach($data_x as $i) {
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
}

function directions($dig, $part) {
	if($part == 1) {
		$dig_dir = $dig[0];
		$dig_lgt = $dig[1];
	} else {
		$dir_char = substr($dig[2], -2, 1);
		$dig_lgt = hexdec(substr($dig[2], 2, 5));
		switch($dir_char) {
			// 0 means R, 1 means D, 2 means L, and 3 means U
			case "0":
				$dig_dir = "R";
				break;
			case "1":
				$dig_dir = "D";
				break;
			case "2":
				$dig_dir = "L";
				break;
			case "3":
				$dig_dir = "U";
				break;
		}
	}
	return [$dig_dir, $dig_lgt];
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
//print_r($data);

$x = 0;
$y = 0;
// make list of x and y actually used, including neighbs
foreach($data as $dig) {
	[$dig_dir, $dig_lgt] = directions($dig, $part);
	if($dig_dir == "L") {
		$x -= $dig_lgt;
	}
	if($dig_dir == "R") {
		$x += $dig_lgt;
	}
	if($dig_dir == "U") {
		$y -= $dig_lgt;
	}
	if($dig_dir == "D") {
		$y += $dig_lgt;
	}
	$data_x[$x-1] = $x-1;
	$data_x[$x] = $x;
	$data_x[$x+1] = $x+1;
	$data_y[$y-1] = $y-1;
	$data_y[$y] = $y;
	$data_y[$y+1] = $y+1;
}
sort($data_x);
sort($data_y);

// construct map of dig
$x = 0;
$y = 0;
$map[$x][$y] = "*"; // will be fixed later
$old_dir = "*"; // undefined
foreach($data as $dig) {
	[$dig_dir, $dig_lgt] = directions($dig, $part);
	if($old_dir != "*") {
		$map[$x][$y] = corner_char($old_dir, $dig_dir);
	}
	for($i=0;$i<$dig_lgt;$i++) {
		switch($dig_dir) {
			case "L":
				$x--;
				$key_of_i = array_search($x, $data_x);
				if($key_of_i === false) {
					// this i isn't interesting
					// skip to the next
					// the one just before it WAS interesting
					$key_of_prev_i = array_search($x + 1, $data_x);
					$this_x = $data_x[$key_of_prev_i - 1];
					$diff = $x - $this_x;
					$x = $this_x;
					$i += $diff;
				}
				break;
			case "R":
				$x++;
				$key_of_i = array_search($x, $data_x);
				if($key_of_i === false) {
					// this i isn't interesting
					// skip to the next
					// the one just before it WAS interesting
					$key_of_prev_i = array_search($x - 1, $data_x);
					$this_x = $data_x[$key_of_prev_i + 1];
					$diff = $this_x - $x;
					$x = $this_x;
					$i += $diff;
				}
				break;
			case "U":
				$y--;
				$key_of_i = array_search($y, $data_y);
				if($key_of_i === false) {
					// this i isn't interesting
					// skip to the next
					// the one just before it WAS interesting
					$key_of_prev_i = array_search($y + 1, $data_y);
					$this_y = $data_y[$key_of_prev_i - 1];
					$diff = $y - $this_y;
					$y = $this_y;
					$i += $diff;
				}
				break;
			case "D":
				$y++;
				$key_of_i = array_search($y, $data_y);
				if($key_of_i === false) {
					// this i isn't interesting
					// skip to the next
					// the one just before it WAS interesting
					$key_of_prev_i = array_search($y - 1, $data_y);
					$this_y = $data_y[$key_of_prev_i + 1];
					$diff = $this_y - $y;
					$y = $this_y;
					$i += $diff;
				}
				break;
		}
		if($i == $dig_lgt - 1) {
			$map[$x][$y] = "*"; // will be fixed later
		} else {
			if($dig_dir == "U" || $dig_dir == "D") {
				$map[$x][$y] = "|";
			} else {
				$map[$x][$y] = "-";
			}
		}
	}
	$old_dir = $dig_dir;
}
// fix the very first bit
$dig_dir = $data[0][0];
$map[$x][$y] = corner_char($old_dir, $dig_dir);

// changes $map
fill_in_map($map, $data_x, $data_y);

// count lava parts of map
$lava_map = 0;
$prev_x = min($data_x) - 1;
$all_chars = max($data_x) - min($data_x) + 1;
foreach($data_x as $x) {
	if($x != $prev_x + 1) {
		// there are missing columns
		// add them anyway
		// how many columns?
		$diff = $x - $prev_x - 1;
		// these are well defined from the previous loop!
		$lava_map += $lava_chars * $diff;
	}
	$prev_y = min($data_y) - 1;
	$lava_chars = 0;
	foreach($data_y as $y) {
		$char = $map[$x][$y];
		if($y != $prev_y + 1) {
			// there are missing rows
			// add them anyway
			// how many rows?
			$diff = $y - $prev_y - 1;
			if($char != ".") {
				$lava_chars += $diff;
			}
		}
		if($char != ".") {
			$lava_chars++;
		}
		$prev_y = $y;
	}
	$lava_map += $lava_chars;
	$prev_x = $x;
}

printf("Size of lava lagoon: %d\n", $lava_map);

?>
