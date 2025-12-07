<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d07input2.txt', true);

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

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$data = pivot($data);
$map_h = sizeof($data[0]);
$map_w = sizeof($data);

// structure to record timelines
for($i=0;$i<$map_w;$i++) {
	$data2[$i] = array_fill(0, $map_h, 0);
}

$splits = 0;
for($j=1;$j<$map_h;$j++) {
	for($i=0;$i<$map_w;$i++) {
		if($data[$i][$j-1] == "S" || $data[$i][$j-1] == "|") {
			// beam incoming
			if($data[$i][$j-1] == "S") {
				$data2[$i][$j-1] = 1;
			}
			if($data[$i][$j] == "^") {
				// beam will split
				$data[$i-1][$j] = "|";
				if(isset($data2[$i-1][$j])) {
					$data2[$i-1][$j] += $data2[$i][$j-1];
				} else {
					$data2[$i-1][$j] = $data2[$i][$j-1];
				}
				$data[$i+1][$j] = "|";
				if(isset($data2[$i+1][$j])) {
					$data2[$i+1][$j] += $data2[$i][$j-1];
				} else {
					$data2[$i+1][$j] = $data2[$i][$j-1];
				}
				$splits++;
			} else {
				$data[$i][$j] = "|";
				if(isset($data2[$i][$j])) {
					$data2[$i][$j] += $data2[$i][$j-1];
				} else {
					$data2[$i][$j] = $data2[$i][$j-1];
				}
			}
		}
	}
}

printf("Result 1: %d\n", $splits);

$timelines = 0;
for($i=0;$i<$map_w;$i++) {
	$timelines += $data2[$i][$map_h-1];
}

printf("Result 2: %d\n", $timelines);

?>
