<?php

$input = "7 6 4 2 1
1 2 7 8 9
9 7 6 2 1
1 3 2 4 5
8 6 4 4 1
1 3 6 7 9";

$safes = 0;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line){
  // do stuff with $line
  // Using preg_match_all to extract numbers
  preg_match_all('/\d+/', $line, $matches);

  // Extracted numbers
  $num = $matches[0];
  
  $dfs = array();
  for($i=1;$i<sizeof($num);$i++) {
  	$dfs[] = $num[$i] - $num[$i-1];
  }
  $safe = 1;
  sort($dfs);
  $fst = $dfs[0];
  $lst = $dfs[sizeof($dfs)-1];
  if($fst * $lst <= 0) {
  	$safe = 0;
  }
  if(1 <= abs($fst) && abs($fst) <= 3 && 1 <= abs($lst) && abs($lst) <= 3) {
  	// safe
  } else {
  	$safe = 0;
  }
  
  if($safe == 1) {
  	$safes++;
  }
}

echo $safes . "\n";

?>
