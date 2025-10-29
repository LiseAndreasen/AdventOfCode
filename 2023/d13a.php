<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d13input2.txt', true);
$smudge = 1;

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
	$map_height = sizeof($map);
	$map_width = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			echo $map[$j][$i];
		}
		echo "\n";
	}
	print("==============================\n");
}

function look_for_reflection($map) {
	// possible spots for reflections line
	// map is w wide
	// line might be between pos 0 and 1
	// pos 1 and 2
	// ...
	// pos w-2 and w-1
	$w = sizeof($map[0]);
	$possible = range(0, $w - 2);
	foreach($map as $line) {
		$line_str = implode("", $line);
		foreach($possible as $pos) {
			$line_lgt = min($pos + 1, $w - $pos - 1);
			$line_begin = strrev(substr($line_str, $pos + 1 - $line_lgt, $line_lgt));
			$line_end = substr($line_str, $pos + 1, $line_lgt);
			if(!strcmp($line_begin, $line_end) == 0) {
				// this option won't work
				unset($possible[$pos]);
			}
		}
	}
	return $possible;
}

///////////////////////////////////////////////////////////////////////////
// main program

// absorb input file, line by line
$i = 0;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$maps[$i][] = str_split($line);
	} else {
		$i++;
	}
}

foreach($maps as $map) {
	$maps_p[] = pivot($map);
}

$sum = 0;
// look for reflections
foreach($maps as $key => $map) {
	// there might be a reflection line vertically
	// look for it in maps
	
	if($smudge == 0) {
		$vert_pos = look_for_reflection($map);
		if(sizeof($vert_pos) == 1) {
			$sum += array_sum($vert_pos) + 1;
		}
	} else {
		$vert_pos_unsmudged = look_for_reflection($map);
		if(sizeof($vert_pos_unsmudged) == 1) {
			$vpu = array_sum($vert_pos_unsmudged);
		} else {
			$vpu = -1;
		}
		$my_map = $map;
		// go through all parts of my_map
		for($i=0;$i<sizeof($my_map);$i++) {
			for($j=0;$j<sizeof($my_map[$i]);$j++) {
				// fix smudge
				if($my_map[$i][$j] == ".") {
					$my_map[$i][$j] = "#";
				} else {
					$my_map[$i][$j] = ".";
				}
				$vert_pos = look_for_reflection($my_map);
				if(isset($vert_pos[$vpu])) {
					unset($vert_pos[$vpu]);
				}
				if(sizeof($vert_pos) == 1) {
					$sum += array_sum($vert_pos) + 1;
					continue 3;
				}
				// this wasn't the smudge, undo fix
				if($my_map[$i][$j] == ".") {
					$my_map[$i][$j] = "#";
				} else {
					$my_map[$i][$j] = ".";
				}				
			}
		}		
	}
	
	// there might be a reflection line horizontally
	// look for it in maps_p

	$map_p = $maps_p[$key];
	if($smudge == 0) {
		$hor_pos = look_for_reflection($map_p);
		if(sizeof($hor_pos) == 1) {
			$sum += (array_sum($hor_pos) + 1) * 100;
		}
	} else {
		$hor_pos_unsmudged = look_for_reflection($map_p);
		if(sizeof($hor_pos_unsmudged) == 1) {
			$hpu = array_sum($hor_pos_unsmudged);
		} else {
			$hpu = -1;
		}
		$my_map = $map_p;
		// go through all parts of my_map
		for($i=0;$i<sizeof($my_map);$i++) {
			for($j=0;$j<sizeof($my_map[$i]);$j++) {
				// fix smudge
				if($my_map[$i][$j] == ".") {
					$my_map[$i][$j] = "#";
				} else {
					$my_map[$i][$j] = ".";
				}
				$hor_pos = look_for_reflection($my_map);
				if(isset($hor_pos[$hpu])) {
					unset($hor_pos[$hpu]);
				}
				if(sizeof($hor_pos) == 1) {
					$sum += (array_sum($hor_pos) + 1) * 100;
					continue 3;
				}
				// this wasn't the smudge, undo fix
				if($my_map[$i][$j] == ".") {
					$my_map[$i][$j] = "#";
				} else {
					$my_map[$i][$j] = ".";
				}				
			}
		}		
	}
}

printf("Sum: $sum\n");

?>
