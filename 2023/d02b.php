<?php

// constants
// $gno: gamenumber
$gno = 0;
// $rch: reaches
$rch = 1;
// $b: blue
// $g: green
// $r: red
$b = 0;
$g = 1;
$r = 2;

//////////////////////////////////////////////

// get input
$input = file_get_contents('./d02input2.txt', true);

$i = 0;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	// Game 1: 3 blue, 4 red; 1 red, 2 green, 6 blue; 2 green
	if(strlen($line)>2) {
		if(preg_match('/Game (\d+):/', $line, $matches)) {
			$games[$i][$gno] = $matches[1];
		}
		if(preg_match_all('/[:;] ([^;]+)/', $line, $matches)) {
			foreach($matches[1] as $key => $tmp) {
				if(preg_match('/(\d+) blue/', $tmp, $matches2)) {
					$games[$i][$rch][$key][$b] = $matches2[1];
				}
				if(preg_match('/(\d+) green/', $tmp, $matches2)) {
					$games[$i][$rch][$key][$g] = $matches2[1];
				}
				if(preg_match('/(\d+) red/', $tmp, $matches2)) {
					$games[$i][$rch][$key][$r] = $matches2[1];
				}
			}
		}
	}
	$i++;
}

$powersum = 0;
foreach($games as $game) {
	$fewest[$b] = 0;
	$fewest[$g] = 0;
	$fewest[$r] = 0;
	$no = $game[$gno];
	foreach($game[$rch] as $reach) {
		if(isset($reach[$b])) {
			$fewest[$b] = max($fewest[$b], $reach[$b]);
		}
		if(isset($reach[$g])) {
			$fewest[$g] = max($fewest[$g], $reach[$g]);
		}
		if(isset($reach[$r])) {
			$fewest[$r] = max($fewest[$r], $reach[$r]);
		}
	}
	$powersum += $fewest[$b] * $fewest[$g] * $fewest[$r];
}

print("Sum of powers: $powersum.\n");

?>
