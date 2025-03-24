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

// The Elf would first like to know which games would have been possible if
// the bag contained only 12 red cubes, 13 green cubes, and 14 blue cubes?

$idsum = 0;
foreach($games as $game) {
	$gamegood = 1;
	$no = $game[$gno];
	foreach($game[$rch] as $reach) {
		if(isset($reach[$b])) {
			if($reach[$b] > 14) {
				$gamegood = 0;
			}
		}
		if(isset($reach[$g])) {
			if($reach[$g] > 13) {
				$gamegood = 0;
			}
		}
		if(isset($reach[$r])) {
			if($reach[$r] > 12) {
				$gamegood = 0;
			}
		}
	}
	if($gamegood == 1) {
		$idsum += $no;
	}
}

print("Sum of ids: $idsum.\n");

?>
