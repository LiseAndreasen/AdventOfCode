<?php

$input1 = "47|53
97|13
97|61
97|47
75|29
61|13
75|53
29|13
97|29
53|29
61|53
97|53
61|29
47|13
75|47
97|75
47|61
75|61
47|29
75|13
53|13

75,47,61,53,29
97,61,53,29,13
75,29,13
75,97,47,61,53
61,13,29
97,13,75,29,47";

$rule = 1;
$rules = array();

function rlchk($book, $rules) {
	$pages = explode(",", $book);
	$k = sizeof($pages);
	for($i=0;$i<$k;$i++) {
		for($j=$i+1;$j<$k;$j++) {
			$dumb = "$pages[$j]|$pages[$i]";
			if(in_array($dumb, $rules)) {
				return 0;
			}
		}
	}
	$mid = ($k - 1)/2;
	return $pages[$mid];
}

function sorting_rule($a, $b) {
	global $rules;
	$dumb1 = "$a|$b";
	$dumb2 = "$b|$a";
	if(in_array($dumb1, $rules)) {
		// a should be before b
		return -1;
	}
	if(in_array($dumb2, $rules)) {
		// b should be before a
		return 1;
	}
	// i have no opinion
	return 0;
}

$sum = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input1) as $line) {
	// do stuff with $line
	if($rule == 1) {
		if(strlen($line) > 0) {
			$rules[] = $line;
		} else {
			$rule = 0;
		}
	} else {
		$valid = rlchk($line, $rules);
		if($valid == 0) {
			$pages = explode(",", $line);
			usort($pages, "sorting_rule");
			$k = sizeof($pages);
			$mid = ($k - 1)/2;
			$sum += $pages[$mid];
		}
	}
}

echo $sum . "\n";

?>
