<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d04input2.txt', true);

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
	global $map_w, $map_h;
	for($j=0;$j<$map_h;$j++) {
		for($i=0;$i<$map_w;$i++) {
			echo $map[$i][$j];
		}
		echo "\n";
	}
	for($i=0;$i<$map_w;$i++) {
		echo "=";
	}
	echo "\n";
}

function remove_paper($even) {
	global $map_w, $map_h;
	global $data;
	
	if($even % 2 == 0) {
		$x = "x";
		$y = "y";
	} else {
		$x = "y";
		$y = "x";
	}
	
	$data2 = $data;

	for($j=0;$j<$map_h;$j++) {
		for($i=0;$i<$map_w;$i++) {
			if($data[$i][$j] == $y) {
				$data2[$i][$j] = ".";
			}
			if($data[$i][$j] == "@") {
				$neighb = 0;
				for($ii=$i-1;$ii<=$i+1;$ii++) {
					for($jj=$j-1;$jj<=$j+1;$jj++) {
						if(isset($data[$ii][$jj]) && ($data[$ii][$jj] == "@")) {
							$neighb++;
						}
					}
				}
				// subtract counting center cell
				$neighb--;
				
				if($neighb < 4) {
					$data2[$i][$j] = $x;
				}
			}
		}
	}

	$arr2 = array_merge(...$data2);
	$arr3 = array_count_values($arr2);
	if(isset($arr3[$x])) {
		$no = $arr3[$x];
	} else {
		$no = 0;
	}
	
	$data = $data2;
	
	return $no;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$data = pivot($data);
$map_h = sizeof($data[0]);
$map_w = sizeof($data);

$remove_sum = 0;
$no = 1;
$i = 1;
while(0 < $no) {
	$no = remove_paper($i);
	printf("Result round %2d: %d\n", $i, $no);
	$i++;
	$remove_sum += $no;
}

printf("Result all.....: %d\n", $remove_sum);

?>
