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
          //$conn3[$comp1][$comp2][$comp3] = 1;
          $conn3b[] = array($comp1, $comp2, $comp3);
        }
      }
    }
  }
}

$allcomp = array_keys($conn2);

$multiple = sizeof($conn3b);
$connx = $conn3b;
while($multiple > 1) {
  print("No. of sets in the iteration: $multiple\n");
  $conny = array();
  // build new arrays of length y from length x - y = x+1
  foreach($connx as $wayx) {
    foreach($allcomp as $key => $newcomp) {
      // test that computer is not already in array
      if(!in_array($newcomp, $wayx)) {
        $newgood = 1;
        // test that new is connected to all in wayx
        foreach($wayx as $oldcomp) {
          if(!isset($conn2[$oldcomp][$newcomp])) {
            $newgood = 0;
            break;
          }
        }
        if($newgood == 1) {
          // create new wayy
          $wayytmp = $wayx;
          array_push($wayytmp, $newcomp);
          sort($wayytmp);
          if(!in_array($wayytmp, $conny)) {
            $conny[] = $wayytmp;
          }
        }
      }
    }
  }
  
  $multiple = sizeof($conny);
  if($multiple == 1) {
    print("Max set found!\n");
    print_r($conny);
  }
  $connx = $conny;
}

?>
