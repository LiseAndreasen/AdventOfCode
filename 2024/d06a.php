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

$dir = "up";
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
			}
			break;
	}
	
}

$steps = 0;
for($i=0;$i<$wid;$i++) {
	for($j=0;$j<$wid;$j++) {
		if($map[$i][$j] == "X") {
			$steps++;
		}
	}
}

echo $steps . "\n";

?>
