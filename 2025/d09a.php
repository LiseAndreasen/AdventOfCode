<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d09input1.txt', true);
$part = 2;

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = explode(",", $line);
		}
	}
	return $data;
}

// is the tile red or green or nothing?
function tile_color($data, $x, $y) {
	// memoization
	global $tile_type;
	if(isset($tile_type[$x][$y])) {
		return $tile_type[$x][$y];
	}
	
	// tile red?
	foreach($data as $key2 => $point) {
		if($point[0] == $x && $point[1] == $y) {
			// tile is red, but which kind?
			if(isset($data[$key2-1])) {
				$key1 = $key2 - 1;
			} else {
				$key1 = sizeof($data) - 1;
			}
			if(isset($data[$key2+1])) {
				$key3 = $key2 + 1;
			} else {
				$key3 = 0;
			}
			[$x1, $y1] = $data[$key1];
			[$x2, $y2] = $data[$key2];
			[$x3, $y3] = $data[$key3];
			if($x1 == $x2) {
				if($y1 < $y2) {
					if($x2 < $x3) {
						$tile_type[$x][$y] = "L";
						return "L";
					} else {
						$tile_type[$x][$y] = "J";
						return "J";
					}
				} else {
					if($x2 < $x3) {
						$tile_type[$x][$y] = "F";
						return "F";
					} else {
						$tile_type[$x][$y] = "7";
						return "7";
					}
				}
			}
			if($x2 == $x3) {
				if($y3 < $y2) {
					if($x2 < $x1) {
						$tile_type[$x][$y] = "L";
						return "L";
					} else {
						$tile_type[$x][$y] = "J";
						return "J";
					}
				} else {
					if($x2 < $x1) {
						$tile_type[$x][$y] = "F";
						return "F";
					} else {
						$tile_type[$x][$y] = "7";
						return "7";
					}
				}
			}
		}
	}

	// tile green?
	$last = sizeof($data);
	$p1 = $data[0];
	$p2 = $data[$last-1];
	if($x == $p1[0] && $x == $p2[0] && min($p1[1], $p2[1]) < $y && $y < max($p1[1], $p2[1])) {
		$tile_type[$x][$y] = "|";
		return "|";
	}
	if($y == $p1[1] && $y == $p2[1] && min($p1[0], $p2[0]) < $x && $x < max($p1[0], $p2[0])) {
		$tile_type[$x][$y] = "-";
		return "-";
	}
	unset($p1);
	foreach($data as $p2) {
		if(!isset($p1)) {
			$p1 = $p2;
			continue;
		}
		if($x == $p1[0] && $x == $p2[0] && min($p1[1], $p2[1]) < $y && $y < max($p1[1], $p2[1])) {
			$tile_type[$x][$y] = "|";
			return "|";
		}
		if($y == $p1[1] && $y == $p2[1] && min($p1[0], $p2[0]) < $x && $x < max($p1[0], $p2[0])) {
			$tile_type[$x][$y] = "-";
			return "-";
		}
		$p1 = $p2;
	}
	
	// tile nothing
	$tile_type[$x][$y] = 0;
	return 0;
}

// heavily borrowed from year 2023, day 10
// test in part 2 that all parts of rectangle are within green tiles

function good_tiles($data, $data_x, $data_y, $x1, $y1, $x2, $y2) {
	global $part;
	if($part == 1) {
		return 1;
	}
	global $tile_type;
	
	// sort corners, top left, bottom right
	$x_tl = min($x1, $x2);
	$x_br = max($x1, $x2);
	$y_tl = min($y1, $y2);
	$y_br = max($y1, $y2);
	
	if($part == 2) {
		// via https://www.desmos.com/calculator/v6ac6opuxg
		// if y_tl < 48547 and y_br > 50233
		// this rectangle will be invalid or very small
		if($y_tl < 48547 && 50233 < $y_br) {
			return 0;
		}
	}
	
	// go through map, testing whether each tile is
	// 1) not part of loop
	// 2) goes through the loop an odd number of times coming from the left

	for($j=$y_tl;$j<=$y_br;$j++) {
		$key_of_j = array_search($j, $data_y);
		if($key_of_j === false) {
			// this j isn't interesting
			// skip to the next
			// the one just before it WAS interesting
			$key_of_prev_j = array_search($j - 1, $data_y);
			$j = $data_y[$key_of_prev_j + 1];
		}
		$tiles_left = 0;
		for($i=0;$i<=$x_br;$i++) {
			$key_of_i = array_search($i, $data_x);
			if($key_of_i === false) {
				// this i isn't interesting
				// skip to the next
				// the one just before it WAS interesting
				// or this is 0
				$key_of_prev_i = array_search($i - 1, $data_x);
				if($key_of_prev_i === false) {
					$i = $data_x[0];
				} else {
					$i = $data_x[$key_of_prev_i + 1];
				}
			}
			$tile_colorness = tile_color($data, $i, $j);
			if($tile_colorness != 0 && $tile_colorness != "O" && $tile_colorness != "I") {
				// tile is not inside or outside or undefined
				if($tile_colorness == "|") {
					$tiles_left++;
				} else {
					// the logic here is, that starting + ending above the line
					// gives a part of the loop, that can be ignored
					// similar for starting and ending below
					// this part starts above or ends below the line
					if($tile_colorness == "L" || $tile_colorness == "7") {
						$tiles_left += 0.5;
					} else {
						// the opposite
						if($tile_colorness == "F" || $tile_colorness == "J") {
							$tiles_left += - 0.5;
						}
					}
				}
			} else {
				if($tile_colorness == "O") {
					// tile is outside
					if($x_tl <= $i && $i <= $x_br && $y_tl <= $j && $j <= $y_br) {
						return 0;
					}
				}
				if(abs($tiles_left) % 2 == 0) {
					// tile is outside
					$tile_type[$i][$j] = "O";
					if($x_tl <= $i && $i <= $x_br && $y_tl <= $j && $j <= $y_br) {
						return 0;
					}
				}
				if(abs($tiles_left) % 2 == 1) {
					// tile is inside
					$tile_type[$i][$j] = "I";
				}
			}
		}
	}
	return 1;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
//print_r($data);
$data_sz = sizeof($data);

// make list of x and y actually used, including neighbs
foreach($data as $p) {
	$data_x[$p[0]-1] = $p[0]-1;
	$data_x[$p[0]] = $p[0];
	$data_x[$p[0]+1] = $p[0]+1;
	$data_y[$p[1]-1] = $p[1]-1;
	$data_y[$p[1]] = $p[1];
	$data_y[$p[1]+1] = $p[1]+1;
}
sort($data_x);
sort($data_y);

// memoization
$tile_type = array();

// go through all rectangles
$rect_sz = 0;
for($i=0;$i<$data_sz-1;$i++) {
	print("\n*"); // progress
	[$x1, $y1] = $data[$i];
	for($j=$i+1;$j<$data_sz;$j++) {
		if($j % 10 == 0) {
			print("."); // progress
		}
		[$x2, $y2] = $data[$j];
		$rect = (abs($x2 - $x1) + 1) * (abs($y2 - $y1) + 1);
		if($rect_sz < $rect) {
			// in part 2, check that the rectangle is on green tiles
			if(good_tiles($data, $data_x, $data_y, $x1, $y1, $x2, $y2)) {
				$rect_sz = $rect;
			}
		}
	}
}
print("\n");

printf("Result: %d\n", $rect_sz);

?>
