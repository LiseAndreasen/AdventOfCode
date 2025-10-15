<?php

// constants

// functions

//////////////////////////////////////////////

$input = file_get_contents('./d09input2.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$sequences[] = explode(" ", $line);
	}
}

// sum of newly generated values
$seq_up_new = 0;
$seq_down_new = 0;

// go through all sequences
foreach($sequences as $key1 => $seq) {
	// for each original sequence, build a tower of sequences
	// initialize to be empty
	$seq_tower = array();
	// number for next level of tower
	$i = 0;
	$seq_tower[$i] = $seq;
	// initialize whether final sequence has been done
	$seq_done = 0;
	
	// build the rest of the tower
	while($seq_done == 0) {
		$stl = sizeof($seq_tower[$i]);
		for($j=0;$j<$stl-1;$j++) {
			$seq_tower[$i+1][$j] = $seq_tower[$i][$j+1] - $seq_tower[$i][$j];
		}
		$counts = array_count_values($seq_tower[$i+1]);
		if(sizeof($counts) == 1 && isset($counts[0])) {
			$seq_done = 1;
		}
		$i++;
	}
	
	// add values at the end and beginning of each tower level
	for($j=$i;$j>=0;$j--) {
		$stl = sizeof($seq_tower[$j]);
		if($j == $i) {
			$seq_tower[$j][$stl] = 0;
			$seq_tower[$j][-1] = 0;
		} else {
			$new_up_val = $seq_tower[$j][$stl - 1] + $seq_tower[$j + 1][$stl - 1];
			$seq_tower[$j][$stl] = $new_up_val;
			$new_down_val = $seq_tower[$j][0] - $seq_tower[$j + 1][-1];
			$seq_tower[$j][-1] = $new_down_val;
		}
	}

	$seq_up_new += $new_up_val;
	$seq_down_new += $new_down_val;
}

print("Sum of new values, added at end..: $seq_up_new\n");
print("Sum of new values, added at start: $seq_down_new\n");

?>
