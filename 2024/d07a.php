<?php

$part = 1;

$input1 = "190: 10 19
3267: 81 40 27
83: 17 5
156: 15 6
7290: 6 8 6 15
161011: 16 10 13
192: 17 8 14
21037: 9 7 18 13
292: 11 6 16 20";

function parse($targ, $res, $addmul, $numss) {
	global $part;
	if(sizeof($numss) == 0) {
		if($targ == $res) {
			return 1;
		} else {
			return 0;
		}
	}
	$tmp = $numss[0];
	unset($numss[0]);
	$numss = array_values($numss);
	if($addmul == "+") {
		$res += $tmp;
	} else {
		if($addmul == "*") {
			$res *= $tmp;
		} else {
			// will not happen in part 1
			$res .= $tmp;
		}
	}
	$valid = parse($targ, $res, "+", $numss);
	if($valid == 1) {
		return 1;
	}
	$valid = parse($targ, $res, "*", $numss);
	if($valid == 1) {
		return 1;
	}
	// only in part 2
	if($part == 2) {
		$valid = parse($targ, $res, "|", $numss);
		if($valid == 1) {
			return 1;
		}
	}
	return 0;
}

$num = array();

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input1) as $line){
	// do stuff with $line
	// Using preg_match_all to extract numbers
	preg_match_all('/\d+/', $line, $num[]);
}

//print_r($num);

$alltarg = 0;
foreach($num as $nums) {
	$numss = $nums[0];
	$targ = $numss[0];
	$res0 = $numss[1];
	unset($numss[0], $numss[1]);
	$numss = array_values($numss);
	//print_r($numss);
	$valid = parse($targ, $res0, "+", $numss);
	if($valid == 1) {
		$alltarg += $targ;
		continue;
	}
	$valid = parse($targ, $res0, "*", $numss);
	if($valid == 1) {
		$alltarg += $targ;
		continue;
	}
	if($part == 2) {
		$valid = parse($targ, $res0, "|", $numss);
		if($valid == 1) {
			$alltarg += $targ;
		}
	}
}

print($alltarg."\n");

?>
