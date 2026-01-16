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

// convert input to points
$x = 0;
$y = 0;
$len = 0;
$p[] = [$x, $y];
foreach($data as $key => $dig) {
	$old_x = $x;
	$old_y = $y;
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
	$p[] = [$x, $y];
	$len += abs($x - $old_x) + abs($y - $old_y);
}

// shoelace inspired approach
$area = 0;
foreach($p as $key => $p2) {
	if($key != 0) {
		$area += ($p1[1] + $p2[1]) * ($p1[0] - $p2[0]) / 2;
	}
	$p1 = $p2;
}

// the area actually needs to grow a little,
// because there's 0.5 added to each length of the perimeter
// len is the length of the perimeter
// this includes m "big" corners and n "small" corners
// a big corner growing 0.5 in each direction adds area 1.25
// a small corner only adds 0.75
// a straight edge adds 0.5
// m = n + 4 (proof by doodling on paper)
// 2m of the length is related to a big corner, add 0.125
// 2n of the length is related to a small corner, subtract 0.125
// area growth: len * 0.5 + 2m * 0.125 - 2n * 0.125
//              len * 0.5 + (2m - 2n) * 0.125
//              len * 0.5 + 2 * 4 * 0.125
//              len * 0.5 + 1
printf("Size of lava lagoon: %d\n", abs($area) + $len * 0.5 + 1);

?>
