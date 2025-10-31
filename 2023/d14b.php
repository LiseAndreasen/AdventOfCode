<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d14input1.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

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
	print("=====================\n");
}

function roll_map($map) {
	foreach($map as $j => $line) {
		if($j == 0) {
			continue;
		}
		foreach($line as $i => $cell) {
			$jj = $j;
			while($map[$i][$jj] == "O" && $map[$i][$jj-1] == ".") {
				$map[$i][$jj] = ".";
				$map[$i][$jj-1] = "O";
				$jj--;
				if($jj == 0) {
					break;
				}
			}
		}
	}
	return $map;
}

function spin($map) {
	//								$map[$x][$y]

	// roll round rocks north/up
	$map = roll_map($map);

	// roll west/left
	$map = pivot($map);				// now y left, x down
	$map = roll_map($map);

	// roll south/down
	$map = array_reverse($map);		// now -y left, x down
	$map = pivot($map);				// now x left, -y down
	$map = roll_map($map);
	$map = pivot($map);				// now -y left, x down
	$map = array_reverse($map);		// now y left, x down

	// roll east/right
	$map = pivot($map);				// now x left, y down
	$map = array_reverse($map);		// now -x left, y down
	$map = pivot($map);				// now y left, -x down
	$map = roll_map($map);
	$map = pivot($map);				// now -x left, y down
	$map = array_reverse($map);		// now x left, y down
	return $map;
}

function calculate_load($map) {
	$map = pivot($map);
	$load_sum = 0;
	// first calculate height
	$h = sizeof($map);

	for($j=0;$j<$h;$j++) {
		$line = $map[$j];
		$load = $h - $j;
		$counts = array_count_values($line);
		if(isset($counts["O"])) {
			$load_sum += $counts["O"] * $load;
		}
	}
	return $load_sum;
}

///////////////////////////////////////////////////////////////////////////
// main program

// absorb input file, line by line
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	//print("$line\n");
	if(strlen($line)>2) {
		$map[] = str_split($line);
	}
}

$map = pivot($map);

$all_maps = array();
// part 2: 1000000000
// theory: the map will become cyclic, being the same after n spins
$spins = 1000000000;
for($i=1;$i<=$spins;$i++) {
	$map = spin($map);
	$map_collapse = implode("", array_merge(...$map));
	if(isset($all_maps[$map_collapse])) {
		break;
	} else {
		$all_maps[$map_collapse] = $i;
	}
}

$spins_part1 = $all_maps[$map_collapse];
$spins_cycle = $i - $spins_part1;
$spins_part2 = floor(($spins - $spins_part1)/$spins_cycle) * $spins_cycle;
$spins_part3 = $spins - $spins_part1 - $spins_part2;
printf("Go %d spins, to reach a state that repeats.\n", $spins_part1);
printf("Go %d spins, in a cycle.\n", $spins_cycle);
printf("Go %d spins, to reach that state again.\n", $spins_part2);
printf("Go %d spins, to finish.\n", $spins_part3);
//print($map_collapse . "\n");
//print_r($all_maps);
//print_map($map);

for($i=1;$i<=$spins_part3;$i++) {
	$map = spin($map);
}

// calculate load of rocks
$load_sum = calculate_load($map);
printf("Load of rocks: %d\n", $load_sum);

?>
