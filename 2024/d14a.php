<?php

$input = file_get_contents('./d14input1.txt', true);
$mapwid = 11;
$maphei = 7;
// test: 11 x 7
// actual: 101 x 103

$robots = array();
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		preg_match_all('/-?\d+/', $line, $robot);
		$robots[] = $robot[0];
	}
}

function print_map() {
	global $robots, $mapwid, $maphei;
	$map = array();
	foreach($robots as $robot) {
		$x = $robot[0];
		$y = $robot[1];
		if(isset($map[$x][$y])) {
			$map[$x][$y]++;
		} else {
			$map[$x][$y] = 1;
		}
	}
	for($j=0;$j<$maphei;$j++) {
		for($i=0;$i<$mapwid;$i++) {
			if(isset($map[$i][$j])) {
				echo $map[$i][$j];
			} else {
				echo ".";
			}
		}
		echo "\n";
	}
	echo "\n";
}

for($i=0;$i<100;$i++) {
	for($j=0;$j<sizeof($robots);$j++) {
		$robots[$j][0] = ($robots[$j][0] + $robots[$j][2] + $mapwid) % $mapwid;
		$robots[$j][1] = ($robots[$j][1] + $robots[$j][3] + $maphei) % $maphei;
	}
}

$robx0y0 = 0;
$robx0y1 = 0;
$robx1y0 = 0;
$robx1y1 = 0;
for($j=0;$j<sizeof($robots);$j++) {
	if($robots[$j][0] < ($mapwid-1)/2) {
		if($robots[$j][1] < ($maphei-1)/2) {
			$robx0y0++;
		} else {
			if($robots[$j][1] > ($maphei-1)/2) {
				$robx0y1++;
			}
		}
	} else {
		if($robots[$j][0] > ($mapwid-1)/2) {
			if($robots[$j][1] < ($maphei-1)/2) {
				$robx1y0++;
			} else {
				if($robots[$j][1] > ($maphei-1)/2) {
					$robx1y1++;
				}
			}
		}
	}
}
//print("$robx0y0 $robx0y1 $robx1y0 $robx1y1\n");
$prod = $robx0y0 * $robx0y1 * $robx1y0 * $robx1y1;
print("$prod\n");
?>
