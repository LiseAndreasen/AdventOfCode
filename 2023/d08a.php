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
	$input = file_get_contents('./d08input1.txt', true);
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
		}
	} else {
		$top_bottom = 0;
	}
}

$i_am_here = "AAA";
$left_right_pos = 0;
$no_of_steps = 0;
$no_of_dir = strlen($left_right);

while(strcmp($i_am_here, "ZZZ") != 0) {
	$next_dir = $left_right[$left_right_pos];
	if(strcmp($next_dir, "L") == 0) {
		$i_am_here = $maps[$i_am_here][$left];
	} else {
		$i_am_here = $maps[$i_am_here][$right];
	}
	$left_right_pos++;
	if($left_right_pos == $no_of_dir) {
		$left_right_pos = 0;
	}
	$no_of_steps++;
}

printf("No of steps: %d\n", $no_of_steps);

?>
