<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d08input1.txt', true);
$pairs = 1000; // 10 or 1000
$part = 2;

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = explode(",", $line);
		}
	}
	return $data;
}

// sort according to own rule
function cmp($a, $b) {
    if ($a[0] == $b[0]) {
        return 0;
    }
    return ($a[0] < $b[0]) ? -1 : 1;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$no_of_boxes = sizeof($data);

// which group am i in?
// the group will be named after the first member
for($i=0;$i<$no_of_boxes;$i++) {
	$my_group[$i] = $i;
	$groups[$i] = array($i);
	$group_sz[$i] = 1;
}

// calculate distances
// only the interesting ones
// and only once each
for($i=0;$i<$no_of_boxes-1;$i++) {
	$x1 = $data[$i][0];
	$y1 = $data[$i][1];
	$z1 = $data[$i][2];
	for($j=$i+1;$j<$no_of_boxes;$j++) {
		$x2 = $data[$j][0];
		$y2 = $data[$j][1];
		$z2 = $data[$j][2];
		$dist = pow(pow($x2 - $x1, 2) + pow($y2 - $y1, 2) + pow($z2 - $z1, 2), 0.5);
		$distances[] = array($dist, $i, $j);
	}
}

usort($distances, "cmp");

$no_done = 0;
$no_comb = 0;
foreach($distances as $distance) {
	$box1 = $distance[1];
	$box2 = $distance[2];
	$group1 = $my_group[$box1];
	$group2 = $my_group[$box2];
	if($group1 != $group2) {
		// move everybody in group 2 to group 1
		foreach($groups[$group2] as $box) {
			$my_group[$box] = $group1;
		}
		$groups[$group1] = array_merge($groups[$group1], $groups[$group2]);
		$groups[$group2] = array();
		$group_sz[$group1] = sizeof($groups[$group1]);
		$group_sz[$group2] = 0;
		$no_comb++;
	}
	$no_done++;
	if($part == 1 && $no_done == $pairs) {
		break;
	}
	if($part == 2 && $no_comb == $no_of_boxes - 1) {
		// box 1 and 2 will be preserved
		break;
	}
}

if($part == 1) {
	rsort($group_sz);
	$prod = $group_sz[0] * $group_sz[1] * $group_sz[2];
	printf("Result 1: %d\n", $prod);
}
if($part == 2) {
	$prod = $data[$box1][0] * $data[$box2][0];
	printf("Result 2: %d\n", $prod);
}

?>
