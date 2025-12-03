<?php

$input = file_get_contents('./d9input1.txt', true);

$line = preg_split("/((\r?\n)|(\r\n?))/", $input);

$dmap = str_split($line[0]);

$lay = array(); // layout
$fno = 0; // file number
$fmode = 1; // file mode, 1 for file, 0 for empty space
$full = array(); // list of files
$empty = array(); // list of empty stretches
for( $i = 0; $i < sizeof($dmap); $i += 1) { 
	if($fmode == 1) {
		$full[] = array(sizeof($lay), $dmap[$i], $fno);
		for($j=0;$j<$dmap[$i];$j++) {
			$lay[] = $fno;
		}
		$fmode = 0;
		$fno++;
	} else {
		$empty[] = array(sizeof($lay), $dmap[$i]);
		for($j=0;$j<$dmap[$i];$j++) {
			$lay[] = ".";
		}
		$fmode = 1;
	}
} 

$full = array_reverse($full);
foreach($full as $file) {
	// find better spot
	$fpos = $file[0];
	$fsiz = $file[1];
	$fval = $file[2];
	$found = 0;
	for($i=0;$i<sizeof($empty);$i++) {
		$epos = $empty[$i][0];
		$esiz = $empty[$i][1];
		if($fpos < $epos) { // don't move the file right
			break;
		}
		if($fsiz <= $esiz) {
			$found = 1;
			break;
		}
	}
	// $i will be used again later
	if($found == 0) {
		continue;
	}
	// copy file
	for($j=$epos;$j<$epos+$fsiz;$j++) {
		$lay[$j] = $fval;
	}
	if($fsiz < $esiz) {
		$empty[$i][0] += $fsiz;
		$empty[$i][1] -= $fsiz;
	} else {
		unset($empty[$i]);
		$empty = array_values($empty);
	}
	// delete old spot
	for($j=$fpos;$j<$fpos+$fsiz;$j++) {
		$lay[$j] = ".";
		// should also update list of empty space, but...
	}
	
}

$sum = 0;
$pos = 0;
for($i=0;$i<sizeof($lay);$i++) {
	if($lay[$i] != ".") {
		$sum += $i * $lay[$i];
	}
}

print("$sum\n");

?>
