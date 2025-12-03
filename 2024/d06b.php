<?php

$input1 = "....#.....
.........#
..........
..#.......
.......#..
..........
.#..^.....
........#.
#.........
......#...";

$map = array();

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input1) as $line){
  // do stuff with $line
  $map[] = str_split($line);
}

$x = 0;
$y = 0;
$wid = sizeof($map);
// find guard
for($i=0;$i<$wid;$i++) {
	$j = array_search("^", $map[$i]);
	if($j) {
		//print("$i $j");
		$x = $i;
		$y = $j;
	}
}

function walkmap($map, $x, $y, $wid) {
	$dir = "up";
	$steps = 0;
	// move guard
	while(0 <= $x && 0 <= $y && $x < $wid && $y < $wid) {
		$map[$x][$y] = "X";
		switch($dir) {
			case "up":
				$x--;
				if($x < 0) {
					break;
				}
				if($map[$x][$y] == "#") {
					$x++;
					$dir = "right";
				} else {
					$steps++;
				}
				break;
			case "down":
				$x++;
				if($x >= $wid) {
					break;
				}
				if($map[$x][$y] == "#") {
					$x--;
					$dir = "left";
				} else {
					$steps++;
				}
				break;
			case "left":
				$y--;
				if($y < 0) {
					break;
				}
				if($map[$x][$y] == "#") {
					$y++;
					$dir = "up";
				} else {
					$steps++;
				}
				break;
			case "right":
				$y++;
				if($y >= $wid) {
					break;
				}
				if($map[$x][$y] == "#") {
					$y--;
					$dir = "down";
				} else {
					$steps++;
				}
				break;
		} // switch
		// if every map cell traversed 2 times
		if($steps > $wid * $wid * 2) {
			break;
		}
	} // while

	return $steps;

} // end function 

$good = 0;

for($i=0;$i<$wid;$i++) {
	for($j=0;$j<$wid;$j++) {
		if($map[$i][$j] == "#" || $map[$i][$j] == "^") {
			continue;
		} else {
			$map[$i][$j] = "#";
			$steps = walkmap($map, $x, $y, $wid);
			$map[$i][$j] = ".";
			if($steps > $wid * $wid * 2) {
				$good++;
			}
		}
	}
}

echo $good . "\n";

?>
