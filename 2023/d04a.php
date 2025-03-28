<?php

//////////////////////////////////////////////

$input = file_get_contents('./d04input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		// Card 1: 41 48 83 86 17 | 83 86  6 31 17  9 48 53
		$line1 = explode(":", $line);
		$line2 = $line1[1];
		$line3 = explode("|", $line2);
		preg_match_all('/\d+/', $line1[0], $no[]);
		// card number
		$cardno = $no[0][0][0];
		$no = array();
		// convert string with divided integers into array
		// all winning numbers
		preg_match_all('/\d+/', $line3[0], $win[$cardno]);
		// all number had
		preg_match_all('/\d+/', $line3[1], $have[$cardno]);
	}
}

// sum of points for cards
$points = 0;
// number of copies of each card
foreach($win as $key => $val) {
	$copies[$key] = 1;
}

foreach($win as $key => $val) {
	$thiswin  = $win[$key][0];
	$thishave = $have[$key][0];
	// i have x winning numbers when wins and haves intersect x times
	$hits = sizeof(array_intersect($thiswin, $thishave));
	if($hits > 0) {
		$points += pow(2, $hits - 1);
	}
	for($i=$key+1;$i<=$key+$hits;$i++) {
		$copies[$i] += $copies[$key];
	}
}
$allcopies = array_sum($copies);

print("Points for all cards: $points\n");
print("Number of cards     : $allcopies\n");

?>
