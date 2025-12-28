<?php

// part 1
// 1st approach
// it works, but awkward

function print_map() {
	global $map, $mapwid, $maphei;
	for($y=0;$y<$maphei;$y++) {
		for($x=0;$x<$mapwid;$x++) {
			if(isset($map[$y][$x])) {
				echo $map[$y][$x];
			} else {
				echo ".";
			}
		}
		echo "\n";
	}
	echo "\n";
}

function find($char) {
	global $map, $mapwid, $maphei;
	for($x=0;$x<$mapwid;$x++) {
		for($y=0;$y<$maphei;$y++) {
			if($map[$y][$x] == $char) {
				return array($x,$y);
			}
		}
	}
}

function walkmap($dir, $x, $y, $score) {
	global $map, $mapwid, $maphei, $scoremin;
	if($scoremin != -1 && $score > $scoremin) {
		return 1;
	}
	switch($dir) {
		case ">":
			if(isempty($map, $x+1, $y)) {
				$map[$y][$x] = ">";
				walkmap($dir, $x+1, $y, $score+1);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x, $y-1)) {
				$map[$y][$x] = "^";
				walkmap("^", $x, $y-1, $score+1000);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x, $y+1)) {
				$map[$y][$x] = "v";
				walkmap("v", $x, $y+1, $score+1000);
				$map[$y][$x] = ".";
			}
			if($map[$y][$x+1] == "E") {
				if($score < $scoremin || $scoremin == -1) {
					$scoremin = $score;
					print_map();
					print("New, better score: $score\n\n");
				}
			}
			break;
		case "^":
			if(isempty($map, $x, $y-1)) {
				$map[$y][$x] = "^";
				walkmap($dir, $x, $y-1, $score+1);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x-1, $y)) {
				$map[$y][$x] = "<";
				walkmap("<", $x-1, $y, $score+1000);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x+1, $y)) {
				$map[$y][$x] = ">";
				walkmap(">", $x+1, $y, $score+1000);
				$map[$y][$x] = ".";
			}
			if($map[$y-1][$x] == "E") {
				if($score < $scoremin || $scoremin == -1) {
					$scoremin = $score;
					print_map();
					print("New, better score: $score\n\n");
				}
			}
			break;
		case "<":
			if(isempty($map, $x-1, $y)) {
				$map[$y][$x] = "<";
				walkmap($dir, $x-1, $y, $score+1);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x, $y-1)) {
				$map[$y][$x] = "^";
				walkmap("^", $x, $y-1, $score+1000);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x, $y+1)) {
				$map[$y][$x] = "v";
				walkmap("v", $x, $y+1, $score+1000);
				$map[$y][$x] = ".";
			}
			if($map[$y][$x-1] == "E") {
				if($score < $scoremin || $scoremin == -1) {
					$scoremin = $score;
					print_map();
					print("New, better score: $score\n\n");
				}
			}
			break;
		case "v":
			if(isempty($map, $x, $y+1)) {
				$map[$y][$x] = "v";
				walkmap($dir, $x, $y+1, $score+1);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x-1, $y)) {
				$map[$y][$x] = "<";
				walkmap("<", $x-1, $y, $score+1000);
				$map[$y][$x] = ".";
			}
			if(isempty($map, $x+1, $y)) {
				$map[$y][$x] = ">";
				walkmap(">", $x+1, $y, $score+1000);
				$map[$y][$x] = ".";
			}
			if($map[$y+1][$x] == "E") {
				if($score < $scoremin || $scoremin == -1) {
					$scoremin = $score;
					print_map();
					print("New, better score: $score\n\n");
				}
			}
			break;
	}
}

function isempty($map, $x, $y) {
	if($map[$y][$x] == ".") {
		return 1;
	} else {
		return 0;
	}
}

////////////////////////////////////////////////////////////////

$input = file_get_contents('./d16input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$map[] = str_split($line);
	}
}
$maphei = sizeof($map);
$mapwid = sizeof($map[0]);

print_map();
print("The map.\n\n");

$coords = find("S");
$x = $coords[0];
$y = $coords[1];
$dir = ">";
$score = 0;
$scoremin = -1;
walkmap($dir, $x, $y, $score);

?>
