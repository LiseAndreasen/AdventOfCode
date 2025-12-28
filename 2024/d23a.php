<?php

//////////////////////////////////

$input = file_get_contents('./d23input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    $conn1[] = explode("-", $line);
  }
}

foreach($conn1 as $comp) {
  $comp1 = $comp[0];
  $comp2 = $comp[1];
  $conn2[$comp1][$comp2] = 1;
  $conn2[$comp2][$comp1] = 1;
}

foreach($conn2 as $comp1 => $c1con) {
  foreach($c1con as $comp2 => $cona) {
    foreach($c1con as $comp3 => $conb) {
      // make sure comp2 and comp3 are different
      if(isset($conn2[$comp2][$comp3])) {
        // only store the 3 way connection alphabetically, so as not to store it 6 times
        if(strcmp($comp1,$comp2) < 0 && strcmp($comp2,$comp3) < 0) {
          // only store this set, if one of the computers begin with t
          if(substr($comp1, 0, 1) == "t" || substr($comp2, 0, 1) == "t" || substr($comp3, 0, 1) == "t") {
            $conn3[$comp1][$comp2][$comp3] = 1;
          }
        }
      }
    }
  }
}

$sum = 0;
foreach($conn3 as $a) {
  foreach($a as $b) {
    foreach($b as $c) {
      $sum += $c;
    }
  }
}
print("$sum\n");

?>
