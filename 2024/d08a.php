<?php

$input = file_get_contents('./d8input1.txt', true);

$map = array();

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	//print("$line\n");
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
$new_map = $map;
foreach($ant as $char) {
	$csz = sizeof($char);
	for($i=0;$i<$csz-1;$i++) {
		for($j=$i+1;$j<$csz;$j++) {
			$x1 = $char[$i][0];
			$y1 = $char[$i][1];
			$x2 = $char[$j][0];
			$y2 = $char[$j][1];
			$x0 = 2*$x1 - $x2;
			$y0 = 2*$y1 - $y2;
			if(0 <= $x0 && $x0 < $msz && 0 <= $y0 && $y0 < $msz) {
				$nod[$x0][$y0] = 1;
			}
			$x3 = 2*$x2 - $x1;
			$y3 = 2*$y2 - $y1;
			if(0 <= $x3 && $x3 < $msz && 0 <= $y3 && $y3 < $msz) {
				$nod[$x3][$y3] = 1;
			}
		}
	}
}

$total = array_sum(array_map("count", $nod));
print($total."\n");

?>
