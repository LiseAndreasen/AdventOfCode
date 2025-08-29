<?php

///////////////////////////////////////////////////////////////////////////
// constants

// test or actual?
$test = 0;

// handy names
$me = 0;
$equals = 1;
$left = 2;
$right = 3;

///////////////////////////////////////////////////////////////////////////
// functions

///////////////////////////////////////////////////////////////////////////
// main program

if($test == 1) {
	$input = file_get_contents('./d08input4.txt', true);
} else {
	$input = file_get_contents('./d08input2.txt', true);
}

$top_bottom = 1;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
//	print("$line\n");
	if(strlen($line)>1) {
		if($top_bottom == 1) {
			$left_right = $line;
		} else {
			// convert string with divider into array
			$line_exp = explode(" ", $line);
			$line_me = $line_exp[$me];
			unset($line_exp[$me]);
			unset($line_exp[$equals]);
			$line_exp[$left] = str_replace("(", "", $line_exp[$left]);
			$line_exp[$left] = str_replace(",", "", $line_exp[$left]);
			$line_exp[$right] = str_replace(")", "", $line_exp[$right]);
			$maps[$line_me] = $line_exp;
			if(strcmp($line_me[2], "A") == 0) {
				$i_am_here[] = $line_me;
			}
		}
	} else {
		$top_bottom = 0;
	}
}

$i_am_a = sizeof($i_am_here);

$left_right_pos = 0;
$no_of_steps = 0;
$no_of_dir = strlen($left_right);
$i_am_z = 0;

while($i_am_z < $i_am_a) {
	$i_am_z = 0;
	$next_dir = $left_right[$left_right_pos];
	foreach($i_am_here as $ikey => $ival) {
		if(strcmp($next_dir, "L") == 0) {
			$i_am_here[$ikey] = $maps[$ival][$left];
		} else {
			$i_am_here[$ikey] = $maps[$ival][$right];
		}
		if(strcmp($i_am_here[$ikey][2], "Z") == 0) {
			$i_am_z++;
			// useful knowledge
			printf("I am z %s Key %d LRpos %d No of steps %d\n",
				$i_am_here[$ikey], $ikey, $left_right_pos, $no_of_steps);
		}
	}
	$left_right_pos++;
	if($left_right_pos == $no_of_dir) {
		$left_right_pos = 0;
	}
	$no_of_steps++;
	
	// program keeps running, stop it
	if($no_of_steps > 100000) { exit(); }
}

printf("No of steps: %d\n", $no_of_steps);

// observations
// key 0 hits a z combination every a steps
// the first time key 0 hits z is after a-1 steps
// key 0 had a z combination, just before I began
// something similar goes for keys 1-5 (there are 6 keys)
// so I am looking for the step where every key has rotated back
// to the "just before I got here" combination
// a = p0 * pp, both prime
// for every a-f, p0-p5 is different, but pp is the same
// I am looking for p0 * p1 * p2 * p3 * p4 * p5 * pp

?>
