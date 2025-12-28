<?php

function dig($design, $level) {
	// towels are at most 8 characters long
	global $towels;
	$hit = 0;
	for($i=8;$i>0;$i--) {
		// if the beginning of this design is towel
		if(in_array(substr($design, 0, $i), $towels)) {
			if(strlen($design) == $i) {
				return 1;
			}
			$hit = dig(substr($design, $i), $level+1);
			if($hit == 1) {
				return 1;
			}
		}
	}
	return 0;
}

///////////////////////////////////////////////////////////

$input = file_get_contents('./d19input1.txt', true);

$phase = 1;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		if($phase == 1) {
			$towels = explode(", ", $line);
		} else {
			$designs[] = $line;
		}
	} else {
		$phase = 2;
	}
}

$hits = 0;
foreach($designs as $design) {
	if(dig($design, 0) > 0) {
		$hits++;
	}
}
echo $hits."\n";

?>
