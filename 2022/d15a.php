<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input_example = false; // example or input?

if($input_example) {
    $input = file_get_contents('./d15input2.txt', true);
    $test_row = 2000000;
} else {
    $input = file_get_contents('./d15input1.txt', true);
    $test_row = 10;
}

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
		    // Sensor at x=2, y=18: closest beacon is at x=-2, y=15
		    preg_match('#(-?\d+)[^-\d]+(-?\d+)[^-\d]+(-?\d+)[^-\d]+(-?\d+)#', $line, $m);
		    [$all, $x1, $y1, $x2, $y2] = $m;
		    $data[] = [$x1, $y1, $x2, $y2];
		}
	}
	return $data;
}

function print_map($map) {
    foreach($map[0] as $j => $cell) {
        printf("%3d ", $j);
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

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

// figure out size of map
foreach ($data as $sensor_beacon) {
    [$x1, $y1, $x2, $y2] = $sensor_beacon;
    $dist = abs($x2 - $x1) + abs($y2 - $y1);
    $all_low_xs[] = $x1 - $dist;
    $all_high_xs[] = $x1 + $dist;
    $all_low_ys[] = $y1 - $dist;
    $all_high_ys[] = $y1 + $dist;
}

$xmin = min($all_low_xs);
$xmax = max($all_high_xs);
$ymin = min($all_low_ys);
$ymax = max($all_high_ys);

// create map of sensors and beacons
$map_col = array_fill($ymin, $ymax - $ymin + 1, ".");
$map = array_fill($xmin, $xmax - $xmin + 1, $map_col);

foreach ($data as $sensor_beacon) {
    [$x1, $y1, $x2, $y2] = $sensor_beacon;
    $map[$x1][$y1] = "S";
    $map[$x2][$y2] = "B";
}

// fill in map with places beacons can't be
$map2 = $map;

foreach ($data as $sensor_beacon) {
    [$x1, $y1, $x2, $y2] = $sensor_beacon;
    $dist = abs($x2 - $x1) + abs($y2 - $y1);
    // i is the x coordinate, travelling from -dist to dist
    for($i=-$dist;$i<=$dist;$i++) {
        // j is the y coordinate
        for($j=-($dist-abs($i));$j<=$dist-abs($i);$j++) {
            $map2[$x1+$i][$y1+$j] = "#";
        }
    }
}

// add the existing sensors and beacons back in
foreach ($data as $sensor_beacon) {
    [$x1, $y1, $x2, $y2] = $sensor_beacon;
    $map2[$x1][$y1] = "S";
    $map2[$x2][$y2] = "B";
}

// testing a single row
$no_beacons_here = 0;
foreach ($map2 as $col) {
    if($col[$test_row] == "#") {
        $no_beacons_here++;
    }
}

printf("Result 1: %d\n", $no_beacons_here);

?>
