<?php

function follow($map, $x, $y, $now) {
	$cnt = "";
	// check this
	if($map[$x][$y] == 9) {
		$cnt .= "($x,$y) ";
		return $cnt;
	}
	// check up
	if($x>0) {
		if($map[$x-1][$y] == $now+1) {
			$cnt .= follow($map, $x-1, $y, $now+1);
		}
	}
	// check right
	if($y<sizeof($map[$x])-1) {
		if($map[$x][$y+1] == $now+1) {
			$cnt .= follow($map, $x, $y+1, $now+1);
		}
	}
	// check down
	if($x<sizeof($map)-1) {
		if($map[$x+1][$y] == $now+1) {
			$cnt .= follow($map, $x+1, $y, $now+1);
		}
	}
	// check left
	if($y>0) {
		if($map[$x][$y-1] == $now+1) {
			$cnt .= follow($map, $x, $y-1, $now+1);
		}
	}
	return $cnt;
}

$input = file_get_contents('./d10input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$map[] = str_split($line);
	}
}

// find all 0s
for($i=0;$i<sizeof($map);$i++) {
	for($j=0;$j<sizeof($map[$i]);$j++) {
		if($map[$i][$j] == 0) {
			$zero[] = array($i, $j);
		}
	}
}

$cnt = 0;
for($i=0;$i<sizeof($zero);$i++) {
	$cnt4 = array();
	$cnttmp = follow($map, $zero[$i][0], $zero[$i][1], 0);
	$cnt1 = explode(" ", $cnttmp);
	foreach($cnt1 as $cnt2) {
		if(strlen($cnt2) > 2) {
			preg_match_all('/\d+/', $cnt2, $cnt3);
			$x = $cnt3[0][0];
			$y = $cnt3[0][1];
			$cnt4[$x][$y] = 1;
		}
	}
	$cnt5 = array_merge(...$cnt4);
	$cnt6 = sizeof($cnt5);
	$cnt += $cnt6;
}

echo $cnt."\n";
?>
