<?php

function newsecret($num1) {
  /*
  secret number s1
  multiply s1 by 64 - s2
  mix s2 into s1 - s3
  prune s3 - s4

  divide s4 by 32 - s5
  round s5 down to integer - s6
  mix s6 into into s4 - s7
  prune s7 - s8

  multiply s8 by 2048 - s9
  mix s9 into s8 - s10
  prune s10 - s11

  mix a into b: bitwise xor a and b
  prune a: a % 16777216
  */
  
  $num4 = (($num1 * 64) ^ $num1) % 16777216;
  $num8 = (floor($num4 / 32) ^ $num4) % 16777216;
  $num11 = (($num8 * 2048) ^ $num8) % 16777216;
  return $num11;
}

///////////////////////////////////////////

$input = file_get_contents('./d22input3.txt', true);
$rounds = 2000;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>0) {
    $secrets[] = (int) $line;
  }
}

// for each buyer
foreach($secrets as $buyer => $numx) {
  // the last 4 changes in price, init
  $numa = 0;
  $numb = 0;
  $numc = 0;
  $numd = 0;
  $numm = 0;
  // for each new secret price
  for($i=0;$i<$rounds;$i++) {
    $numy = newsecret($numx);
    $numn = $numy % 10;
    $numa = $numb;
    $numb = $numc;
    $numc = $numd;
    $numd = $numn - $numm;
    // only if we have 4 changes / 5 prices / i >= 4
    if($i>=4) {
      if(!isset($changes[$numa][$numb][$numc][$numd][$buyer])) {
        $changes[$numa][$numb][$numc][$numd][$buyer] = $numn;
      }
    }
    $numx = $numy;
    $numm = $numn;
  }
}

$bestsum = 0;
// for each sequence, calculate sum
// remember best sum
for($a=-9;$a<=9;$a++) {
  for($b=-9;$b<=9;$b++) {
    for($c=-9;$c<=9;$c++) {
      for($d=-9;$d<=9;$d++) {
        if(isset($changes[$a][$b][$c][$d])) {
          $tmpsum = array_sum($changes[$a][$b][$c][$d]);
          if($tmpsum > $bestsum) {
            $bestsum = $tmpsum;
            $bestseq = "($a,$b,$c,$d)";
          }
        }
      }
    }
  }
}

print("$bestsum $bestseq\n");

?>
