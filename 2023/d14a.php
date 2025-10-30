<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d14input2.txt', true);

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
//print_map($map);
//print("=====================\n");

// roll round rocks north/up
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

//print_map($map);
$map = pivot($map);

// calculate load of rocks
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

printf("Load of rocks: %d\n", $load_sum);

?>
