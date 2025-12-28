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

$input = file_get_contents('./d22input1.txt', true);
$rounds = 2000;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>0) {
    $secrets[] = (int) $line;
  }
}

$sum = 0;
foreach($secrets as $num) {
  for($i=0;$i<$rounds;$i++) {
    $num = newsecret($num);
  }
  // print("$num\n");
  $sum += $num;
}
print("$sum\n");
?>
