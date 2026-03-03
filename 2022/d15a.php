<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input_example = true; // example or input?

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

// Source - https://stackoverflow.com/a/3631016
// Posted by Matthew
// Retrieved 2026-03-03, License - CC BY-SA 2.5
function merge_data($data) {
    usort($data, function($a, $b)
    {
        return $a[0] - $b[0];
    });
    
    $n = 0; $len = count($data);
    for ($i = 1; $i < $len; ++$i)
    {
        if ($data[$i][0] > $data[$n][1] + 1)
            $n = $i;
            else
            {
                if ($data[$n][1] < $data[$i][1])
                    $data[$n][1] = $data[$i][1];
                    unset($data[$i]);
            }
    }
    
    $data = array_values($data);
    return $data;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

// figure out size of map
// begin constructing the test row
foreach ($data as $sensor_beacon) {
    [$x1, $y1, $x2, $y2] = $sensor_beacon;
    $dist = abs($x2 - $x1) + abs($y2 - $y1);
    $all_low_xs[] = $x1 - $dist;
    $all_high_xs[] = $x1 + $dist;
    $all_low_ys[] = $y1 - $dist;
    $all_high_ys[] = $y1 + $dist;
    
    if($y1 == $test_row) {
        $test_row_ranges_keep[] = [$x1, $x1, "S"];
    }
    if($y2 == $test_row) {
        $test_row_ranges_keep[] = [$x2, $x2, "B"];
    }
}

$xmin = min($all_low_xs);
$xmax = max($all_high_xs);
$ymin = min($all_low_ys);
$ymax = max($all_high_ys);

// a sensor at x1, y1 at a distance dist from the beacon
// will also cover all these ranges, where a + b = dist
// 0 < a, b
// (x1 - a, y1 - b) -> (x1 + a, y1 - b)
// (x1 - a, y1 + b) -> (x1 + a, y1 + b)

// continue work on the test row
foreach ($data as $sensor_beacon) {
    [$x1, $y1, $x2, $y2] = $sensor_beacon;
    $dist = abs($x2 - $x1) + abs($y2 - $y1);
    // this area goes from y1 - dist -> y1 + dist
    if($y1 - $dist <= $test_row && $test_row <= $y1 + $dist) {
        $b = abs($test_row - $y1);
        $a = $dist - $b;
        $test_row_ranges[] = [$x1 - $a, $x1 + $a, "#"];
    }
}

// among the ranges, it is important never to delete sensors and beacons
// assumption: that any sensors and beacons are within other ranges
$test_row_ranges_keep = merge_data($test_row_ranges_keep);
$test_row_ranges = merge_data($test_row_ranges);

$length_of_ranges = 0;
foreach ($test_row_ranges_keep as $trr) {
    $length_of_ranges -= $trr[1] - $trr[0] + 1;
}
foreach ($test_row_ranges as $trr) {
    $length_of_ranges += $trr[1] - $trr[0] + 1;
}

printf("Result 1: %d\n", $length_of_ranges);

?>
