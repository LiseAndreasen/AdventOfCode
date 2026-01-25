<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d21input2.txt', true);

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

function take_step($positions, $map) {
	foreach($positions as $x => $pos_col) {
		foreach($pos_col as $y => $pos_cell) {
			if(isset($map[$x-1][$y]) && $map[$x-1][$y] != "#") {
				$new_positions[$x-1][$y] = 1;
			}
			if(isset($map[$x+1][$y]) && $map[$x+1][$y] != "#") {
				$new_positions[$x+1][$y] = 1;
			}
			if(isset($map[$x][$y-1]) && $map[$x][$y-1] != "#") {
				$new_positions[$x][$y-1] = 1;
			}
			if(isset($map[$x][$y+1]) && $map[$x][$y+1] != "#") {
				$new_positions[$x][$y+1] = 1;
			}
		}
	}
	return $new_positions;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = pivot(get_input($input));

// find S
for($i=0;$i<sizeof($data);$i++) {
	$j = array_search("S", $data[$i]);
	if($j !== false) {
		$S_x = $i;
		$S_y = $j;
	}
}

$pos[$S_x][$S_y] = 1;
for($i=1;$i<=64;$i++) {
	$pos = take_step($pos, $data);
}

$pos2 = array_merge(...$pos);

printf("Result 1: %d\n", sizeof($pos2));

///////////////////////////////////////////////////////////////////////////
// another approach - uses wrapping of the map
// heavily borrowed from ShaneMcC
// annoyed that I don't know why this works

// create map with 5x5 copies
$data_empty = $data;
$data_empty[$S_x][$S_y] = ".";
foreach($data_empty as $col) {
	$data_5x1[] = array_merge($col, $col, $col, $col, $col);
}
$data_5x1 = pivot($data_5x1);
foreach($data_5x1 as $col) {
	$data_5x5[] = array_merge($col, $col, $col, $col, $col);
}
$data_5x5 = pivot($data_5x5);
$middle = 131 * 2 + 65;
$data_5x5[$middle][$middle] = "S";
$pos_5x5[$middle][$middle] = 1;

$pos_5x5t1 = $pos_5x5;
for($i=1;$i<=65;$i++) {
	$pos_5x5t1 = take_step($pos_5x5t1, $data_5x5);
}
$t1arr = array_merge(...$pos_5x5t1);
$t1 = sizeof($t1arr);

$pos_5x5t2 = $pos_5x5;
// 65 + 131 = 196
for($i=1;$i<=196;$i++) {
	$pos_5x5t2 = take_step($pos_5x5t2, $data_5x5);
}
$t2arr = array_merge(...$pos_5x5t2);
$t2 = sizeof($t2arr);

$pos_5x5t3 = $pos_5x5;
// 65 + 2 * 131 = 327
for($i=1;$i<=327;$i++) {
	$pos_5x5t3 = take_step($pos_5x5t3, $data_5x5);
}
$t3arr = array_merge(...$pos_5x5t3);
$t3 = sizeof($t3arr);

$x = intval(26501365 / count($data[0]));
$delta = ($t3 - $t2) - ($t2 - $t1);
$step = $t2 - $t1;
$part2 = $t1;
for ($i = 0; $i < $x; $i++) {
	$part2 += $step;
	$step += $delta;
}
echo 'Result 2: ', $part2, "\n";

?>
