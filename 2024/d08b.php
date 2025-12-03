<?php

$input = file_get_contents('./d8input1.txt', true);

$map = array();

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line) > 2) {
		$map[] = str_split($line);
	}
}

$msz = sizeof($map);
$ant = array();
for($i=0;$i<$msz;$i++) {
	for($j=0;$j<$msz;$j++) {
		$char = $map[$i][$j];
		if($char == ".") {
			continue;
		}
		$ant[$char][] = array($i, $j);
	}
}

$nod = array();
foreach($ant as $char) {
	//print_r($char);
	$csz = sizeof($char);
	for($i=0;$i<$csz-1;$i++) {
		for($j=$i+1;$j<$csz;$j++) {
			$x1 = $char[$i][0];
			$y1 = $char[$i][1];
			$x2 = $char[$j][0];
			$y2 = $char[$j][1];
			$nod[$x1][$y1] = 1;
			$nod[$x2][$y2] = 1;
			$xdiff = $x2 - $x1;
			$ydiff = $y2 - $y1;
			// roll x left
			$xtmp = $x1;
			$ytmp = $y1;
			while(0 <= $xtmp && $xtmp < $msz && 0 <= $ytmp && $ytmp < $msz) {
				$nod[$xtmp][$ytmp] = 1;
				$xtmp -= $xdiff;
				$ytmp -= $ydiff;
			}
			// roll x right
			$xtmp = $x2;
			$ytmp = $y2;
			while(0 <= $xtmp && $xtmp < $msz && 0 <= $ytmp && $ytmp < $msz) {
				$nod[$xtmp][$ytmp] = 1;
				$xtmp += $xdiff;
				$ytmp += $ydiff;
			}
		}
	}
}

$total = array_sum(array_map("count", $nod));
print($total."\n");

?>
