<?php

$input = "3   4
4   3
2   5
1   3
3   9
3   3";

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line){
  // do stuff with $line
  // Using preg_match_all to extract numbers
  preg_match_all('/\d+/', $line, $matches);

  // Extracted numbers
  $numbers = $matches[0];
  
  if(sizeof($numbers) != 2) {
  	exit();
  }
  
  $list1[] = $numbers[0];
  $list2[] = $numbers[1];

}

sort($list1);
sort($list2);

$distance = 0;
for($i=0;$i<sizeof($list1);$i++) {
	$distance += abs($list1[$i] - $list2[$i]);
}

echo $distance . "\n";
?>
