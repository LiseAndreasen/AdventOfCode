<?php

// constants
$steps = array("seed", "soil", "fertilizer", "water", "light", "temperature", "humidity", "location");
$stepno = sizeof($steps);

$startid = 0;
$rangeid = 1;

// map numbers: destination, source, range
$md = 0;
$ms = 1;
$mr = 2;

// functions

function fill_up_maps() {
	global $maps, $stepno, $startid, $rangeid, $md, $ms, $mr;
	for($i=0;$i<$stepno-1;$i++) {
		$ends[] = -1;
		$starts[] = 100000000000000;
		foreach($maps[$i] as $map) {
			$starts[] = $map[0][$ms];
			$ends[] = $map[0][$ms] + $map[0][$mr] - 1;
		}
		// close all gaps
		foreach($ends as $key => $end) {
			$gap = array_search($end + 1, $starts);
			if($gap != false) {
				unset($ends[$key]);
				unset($starts[$gap]);
			}
		}
		sort($starts);
		sort($ends);
		while(sizeof($starts) > 0) {
			$thisend = $ends[0];
			$nextstart = $starts[0];
			$maps[$i][] = array(array($thisend + 1, $thisend + 1, $nextstart - $thisend - 1));
			unset($starts[0]);
			unset($ends[0]);
			sort($starts);
			sort($ends);
		}
	}
}

function find_maps($stepid, $stretch) {
	global $maps, $stretches, $startid, $rangeid;
	$sstart = $stretches[$stretch][$startid];
	$srange = $stretches[$stretch][$rangeid];
	$keys = array();
	foreach($maps[$stepid] as $key => $map) {
		$m = $map[0];
		$mstart = $m[1];
		$mrange = $m[2];
		$thiskey = 0;
		// case 1
		if($mstart <= $sstart
		&& $sstart + $srange - 1 <= $mstart + $mrange - 1) {
			$thiskey = 1;
		}
		// case 2
		if($mstart <= $sstart
		&& $sstart <= $mstart + $mrange - 1
		&& $mstart + $mrange - 1 < $sstart + $srange - 1) {
			$thiskey = 2;
		}
		// case 3
		if($sstart < $mstart
		&& $mstart <= $sstart + $srange -1
		&& $sstart + $srange - 1 <= $mstart + $mrange -1) {
			$thiskey = 3;
		}
		// case 4
		if($sstart < $mstart
		&& $mstart + $mrange - 1 < $sstart + $srange - 1) {
			$thiskey = 4;
		}
		if($thiskey > 0) {
			$keys[] = array($key, $thiskey);
		}
	}
	return $keys;
}

//////////////////////////////////////////////

// a stretch includes seed, soil, fertilizer etc.

// stretch.: a b
// map.....: x c d

// case 1, map covers whole stretch:
//             a        a+b-1
//             *--------*
//           c             c+d-1
//           *-------------*
// c <= a (<=) a+b-1 <= c+d-1
// new stretch:
// a-c+x , b

// case 2, map covers beginning of stretch:
//             a        a+b-1
//             *--------*
//           c   c+d-1
//           *---*
// c <= a <= c+d-1 < a+b-1
// new stretch:
// a-c+x, c+d-a

// case 3, map covers end of stretch:
//             a        a+b-1
//             *--------*
//                    c   c+d-1
//                    *---*
// a < c <= a+b-1 <= c+d-1
// new stretch:
// x, a+b-c

// case 4, map covers middle of stretch:
//             a        a+b-1
//             *--------*
//               c   c+d-1
//               *---*
// a < c (<=) c+d-1 < a+b-1
// new stretch:
// x, d

//////////////////////////////////////////////

$input = file_get_contents('./d05input1.txt', true);

$step = -1;
$mapmode = 0;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	// seeds and ranges
	if(strlen($line)>2 && $step == -1 && preg_match('/seeds/', $line, $matches)) {
		// convert string with divided integers into array
		preg_match_all('/\d+/', $line, $seeds);
		$step++;
	}
	
	// maps
	// first line
	$str = $steps[$step] . "-to-" . $steps[$step+1];
	if(strlen($line)>2 && $step > -1 && preg_match("/$str/", $line, $matches)) {
		$mapmode = 1;
		continue;
	}
	// the maps
	if(strlen($line)>2 && $step > -1 && $mapmode == 1) {
		preg_match_all('/\d+/', $line, $maps[$step][]);
	}
	// no more maps
	if(strlen($line)<=2 && $step > -1 && $mapmode == 1) {
		$step++;
		$mapmode = 0;
	}
}

fill_up_maps();

// create the stretches
for($i=0;$i<sizeof($seeds[0]);$i+=2) {
	$srno = $i / 2;
	$stretches[$srno][$startid] = $seeds[0][$i];
	$stretches[$srno][$rangeid] = $seeds[0][$i+1];
	$stretches[$srno][2] = array($i / 2); // org id, for TEST purposes
}

// convert the stretches through the maps

// check every step for maps
for($i=0;$i<$stepno-1;$i++) {
	$stretches2 = array();
	// convert each stretch
	foreach($stretches as $key => $stretchrange) {
		$sstart = $stretchrange[$startid];
		$srange = $stretchrange[$rangeid];
		
		//seed-to-soil map:
		//50 98 2
		// 1st number: soil
		// 2nd number: seed range, 1st number
		// 3rd number: range length
		// the seed range is 98-99, 2 numbers
		// the soil range is 50-51, 2 numbers
	
		// begin by fetching the relevant maps
		$mapnos = find_maps($i, $key);
		foreach($mapnos as $mapno) {
			$mid = $mapno[0];
			$mcase = $mapno[1];		
			$m = $maps[$i][$mid][0];
			$mdest = $m[$md];
			$mstart = $m[$ms];
			$mrange = $m[$mr];

			switch ($mcase) {
				case 1:
					$stretches2[] = array($sstart - $mstart + $mdest, $srange);
					break;
				case 2:
					$stretches2[] = array($sstart - $mstart + $mdest, $mstart + $mrange - $sstart);
					break;
				case 3:
					$stretches2[] = array($mdest, $sstart + $srange - $mstart);
					break;
				case 4:
					$stretches2[] = array($mdest, $mrange);
					break;
			}
		}
	}
	$stretches = $stretches2;
}

foreach($stretches as $stretch) {
	$locs[] = $stretch[$startid];
}
$lowest = min($locs);

print("Lowest location: $lowest\n");
?>
