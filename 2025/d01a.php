<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d01input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>1) {
			$map[] = array($line[0], substr($line, 1));
		}
	}
	return $map;
}

///////////////////////////////////////////////////////////////////////////
// main program

$map = get_input($input);

$begin = 50;
$no = 100;

$pos = $begin;
$hits0 = 0;
$hits_all = 0;
foreach($map as $dirlgt) {
	$old_pos = $pos;
	$dir = $dirlgt[0];
	$lgt = $dirlgt[1];
	if($dir == "L") {
		$new_pos = $old_pos - $lgt;
		if($new_pos < 0) {
			$times = ceil(abs($new_pos) / $no);
			$new_pos += $times * $no;
			$hits_all += $times;
			if($old_pos == 0) {
				// one of the hits have already been counted
				$hits_all--;
			}
		}
	} else {
		$new_pos = $old_pos + $lgt;
		if($no <= $new_pos) {
			$times = floor(($new_pos) / $no);
			$new_pos -= $times * $no;
			$hits_all += $times;
			if($new_pos == 0) {
				// one of the hits will be counted again
				$hits_all--;
			}
		}
	}
	$pos = $new_pos;
	if($new_pos == 0) {
		$hits0++;
		$hits_all++;
	}
}

printf("No of hits to exactly 0: %5d\n", $hits0);
printf("No of hits to go by 0..: %5d\n", $hits_all); // 5660 too high

?>
