<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d22input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			// 1,0,1~1,2,1
			// pattern is between #
			// (): subpattern within
			// . is 1 character
			// S: speed up?
			// A: match to beginning of string
			// D: how to handle $
			// i: match both upper and lower case
			preg_match('#(.*),(.*),(.*)~(.*),(.*),(.*)#SADi', $line, $m);
			[$all, $x1, $y1, $z1, $x2, $y2, $z2] = $m;
			$data[] = [$x1, $y1, $z1, $x2, $y2, $z2];
		}
	}
	return $data;
}

function brick_name($num) {
	// assuming $num < 52 * 52
	// 26 upper case letters, 26 lower case
	// 65-90, 97-122
	$num1 = floor($num / 52) + 65;
	$num2 = $num % 52 + 65;
	if(91 <= $num1) {
		$num1 += 6;
	}
	if(91 <= $num2) {
		$num2 += 6;
	}
	return chr($num1) . chr($num2);
}

// makes most sense for test data
function print_tower($tower, $min, $max) {
	$xys = ["x", "y"];

	// first line
	foreach($xys as $xy) {
		print("$xy");
		for($ij=$min[$xy];$ij<=$max[$xy];$ij++) {
			print("  ");
		}
	}
	print("\n");

	// second line
	foreach($xys as $xy) {
		for($ij=$min[$xy];$ij<=$max[$xy];$ij++) {
			printf("%2d", $ij);
		}
		print(" ");
	}
	print("  z\n");

	// 1 line for each z
	for($k=$max["z"];1<=$k;$k--) {
		foreach($xys as $key => $xy) {
			if($key == 0) {
				$yx = $xys[1];
			} else {
				$yx = $xys[0];
			}
			for($ij=$min[$xy];$ij<=$max[$xy];$ij++) {
				// brick found and printed? not yet
				$it_is_set = 0;
				// if x is set, y changes, and vice versa
				$ji = 0;
				// find and print brick
				while($it_is_set == 0) {
					// nonsense to handle both cases of x and y
					if($xy == "x") {
						$ijk_set = isset($tower[$ij][$ji][$k]);
						if($ijk_set) {
							$ijk_val = $tower[$ij][$ji][$k];
						}
					} else {
						$ijk_set = isset($tower[$ji][$ij][$k]);
						if($ijk_set) {
							$ijk_val = $tower[$ji][$ij][$k];
						}
					}
					if($ijk_set) {
						// brick found, print it
						printf("%2s", $ijk_val);
						$it_is_set = 1;
					} else {
						if($max[$yx] < $ji) {
							// no bricks
							print("..");
							$it_is_set = 2;
						} else {
							// keep looking for brick
							$ji++;
						}
					}
				}
			}
			print(" ");
		}
		printf("%3d\n", $k);
	}

	// last line
	foreach($xys as $xy) {
		for($ij=$min[$xy];$ij<=$max[$xy];$ij++) {
			print("--");
		}
		print(" ");
	}
	print("  0\n\n");
}

function data_2_tower($data) {
	// create 3d structure for all bricks
	foreach($data as $key => $brick) {
		// letters representing brick
		$brick_id = brick_name($key);
		[$x1, $y1, $z1, $x2, $y2, $z2] = $brick;
		for($i=$x1;$i<=$x2;$i++) {
			for($j=$y1;$j<=$y2;$j++) {
				// important
				// low z first
				$z_min = min($z1, $z2);
				$z_max = max($z1, $z2);
				for($k=$z_min;$k<=$z_max;$k++) {
					// for each cube
					// register cube as part of brick
					$bricks[$brick_id][] = [$i, $j, $k];
					// and as part of tower
					$tower[$i][$j][$k] = $brick_id;
			// register min and max - for z, y, x
					if(!isset($min["z"]) || $k < $min["z"]) {
						$min["z"] = $k;
					}
					if(!isset($max["z"]) || $max["z"] < $k) {
						$max["z"] = $k;
					}
				}
				if(!isset($min["y"]) || $j < $min["y"]) {
					$min["y"] = $j;
				}
				if(!isset($max["y"]) || $max["y"] < $j) {
					$max["y"] = $j;
				}
			}
			if(!isset($min["x"]) || $i < $min["x"]) {
				$min["x"] = $i;
			}
			if(!isset($max["x"]) || $max["x"] < $i) {
				$max["x"] = $i;
			}
		}
	}
	return [$bricks, $tower, $min, $max];
}

function drop_bricks($tower, $bricks) {
	$dropped = [];
	// drops did occur in this round?
	$drops = 1;
	while($drops == 1) {
		$drops = 0;
		foreach($bricks as $brick_id => $brick) {
			$brick_can_drop = 1;
			foreach($brick as $cube) {
				[$x, $y, $z] = $cube;
				if(
				(isset($tower[$x][$y][$z-1])
				&& strcmp($tower[$x][$y][$z-1], $brick_id) != 0)
				|| 1 == $z) {
					$brick_can_drop = 0;
					break;
				}
			} // for each cube in brick
			if($brick_can_drop == 1) {
				$dropped[$brick_id] = $brick_id;
				foreach($brick as $c => $cube) {
					[$x, $y, $z] = $cube;
					// decrease z coordinate
					$bricks[$brick_id][$c][2]--;
					unset($tower[$x][$y][$z]);
					$tower[$x][$y][$z-1] = $brick_id;
				}
				$drops = 1;
			} // brick dropping or not
		} // for each brick
	} // try for any drops
	return [$tower, $bricks, sizeof($dropped)];
}

function bricks_will_fall($brick_id, $supports, $rests_on) {
	$num_falls = 0;
	if(isset($supports[$brick_id])) {
		// for each brick i am supporting
		foreach($supports[$brick_id] as $brick2) {
			// am i the only one?
			if(sizeof($rests_on[$brick2]) == 1) {
				$num_falls++;
			}
		}
		return $num_falls;
	} else {
		// this brick doesn't support anything
		return 0;
	}
}

function analyze_bricks($tower, $bricks) {
	foreach($bricks as $brick_id => $brick) {
		foreach($brick as $cube) {
			[$x, $y, $z] = $cube;
			if(isset($tower[$x][$y][$z-1])) {
				// char rests on $tower[$x][$y][$z-1]
				// resting on yourself doesn't count
				if(strcmp($tower[$x][$y][$z-1], $brick_id) != 0) {
					$rests_on[$brick_id][$tower[$x][$y][$z-1]] = $tower[$x][$y][$z-1];
					$supports[$tower[$x][$y][$z-1]][$brick_id] = $brick_id;
				}
			}
		}
	}
	return [$supports, $rests_on];
}

function number_of_safe_bricks($tower, $bricks, $supports, $rests_on) {
	$safe_bricks = 0;
	foreach($bricks as $brick_id => $brick) {
		$num_falls = bricks_will_fall($brick_id, $supports, $rests_on, 1);
		if(0 == $num_falls) {
			$safe_bricks++;
		}
	}	
	return $safe_bricks;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

[$bricks, $tower, $min, $max] = data_2_tower($data);

[$tower, $bricks, $dropped] = drop_bricks($tower, $bricks);

[$supports, $rests_on] = analyze_bricks($tower, $bricks);

$num = number_of_safe_bricks($tower, $bricks, $supports, $rests_on);

printf("Result 1: %d\n", $num);

$num = 0;
foreach($bricks as $brick_id => $brick) {
	print($brick_id); // progress
	// remove brick, drop the rest, count how many actually dropped
	$tower_less = $tower;
	$bricks_less = $bricks;
	foreach($brick as $cube) {
		[$x, $y, $z] = $cube;
		unset($tower_less[$x][$y][$z]);
	}
	unset($bricks_less[$brick_id]);
	[$t, $b, $dropped] = drop_bricks($tower_less, $bricks_less);
	$num += $dropped;
}
print("\n");

printf("Result 2: %d\n", $num);

?>
