<?php

// constants
// gear existance
$gex = 0;
// gear neighbors
$gne = 1;

// change (y, x) map to (x, y) map
function pivot() {
	global $map, $mapwid, $maphei;
	for($j=0;$j<$maphei;$j++) {
		for($i=0;$i<$mapwid;$i++) {
			$map2[$j][$i] = $map[$i][$j];
		}
	}
	$map = $map2;
}

// add extra space around the map
function fillup() {
	global $map, $mapwid, $maphei;
	for($i=-1;$i<=$mapwid;$i++) {
		$map[$i][-1] = ".";
		$map[$i][$maphei] = ".";
	}
	for($j=-1;$j<=$maphei;$j++) {
		$map[-1][$j] = ".";
		$map[$mapwid][$j] = ".";
	}
}

function print_map() {
	global $map, $mapwid, $maphei;
	for($j=-1;$j<=$maphei;$j++) {
		for($i=-1;$i<=$mapwid;$i++) {
			echo $map[$i][$j];
		}
		echo "\n";
	}
}

function is_sym($char) {
	if($char == "." || is_numeric($char)) {
		return 0;
	} else {
		return 1;
	}
}

function no_has_sym($x, $y, $l, $num) {
	global $map, $gears, $gne;
	// number begins at (x, y)
	// number is l long
	// there should be a symbol somewhere between
	// (x-1, y-1) and (x+l, y+1)
	
	// was a symbol detected?
	$symbol = 0;
	if(is_sym($map[$x-1][$y])) {
		$symbol = 1;
		if($map[$x-1][$y] == "*") {
			$gears[$x-1][$y][$gne][] = $num;
		}
	}
	if(is_sym($map[$x+$l][$y])) {
		$symbol = 1;
		if($map[$x+$l][$y] == "*") {
			$gears[$x+$l][$y][$gne][] = $num;
		}
	}
	for($i=$x-1;$i<=$x+$l;$i++) {
		if(is_sym($map[$i][$y-1])) {
			$symbol = 1;
			if($map[$i][$y-1] == "*") {
				$gears[$i][$y-1][$gne][] = $num;
			}
		}
		if(is_sym($map[$i][$y+1])) {
			$symbol = 1;
			if($map[$i][$y+1] == "*") {
				$gears[$i][$y+1][$gne][] = $num;
			}
		}
	}
	return $symbol;
}

//////////////////////////////////////////////

$input = file_get_contents('./d03input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	//print("$line\n");
	if(strlen($line)>2) {
		$map[] = str_split($line);
	}
}

$maphei = sizeof($map);
$mapwid = sizeof($map[0]);

pivot();
fillup();

// find all gears
for($i=0;$i<$mapwid;$i++) {
	for($j=0;$j<$maphei;$j++) {
		if($map[$i][$j] == "*") {
			$gears[$i][$j][$gex] = 1;
		}
	}
}

// sum of part numbers
$numsum = 0;
for($i=0;$i<$mapwid;$i++) {
	for($j=0;$j<$maphei;$j++) {
		if(is_numeric($map[$i][$j]) && !is_numeric($map[$i-1][$j])) {
			$num = $map[$i][$j];
			$l=1;
			while(is_numeric($map[$i+$l][$j])) {
				$num .= $map[$i+$l][$j];
				$l++;
			}
			if(no_has_sym($i, $j, $l, $num)) {
				$numsum += $num;
			}
		}
	}
}

// gear ratios
$ratsum = 0;
foreach($gears as $gx => $gearss) {
	foreach($gearss as $gy => $gearsss) {
		if(sizeof($gearsss[$gne]) == 2) {
			$ratsum += $gearsss[$gne][0] * $gearsss[$gne][1];
		}
	}
}

print("Sum of valid part numbers: $numsum\n");
print("Sum of gear ratios.......: $ratsum\n");

?>
