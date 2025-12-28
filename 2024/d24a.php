<?php

///////////////////////////////////////

$input = file_get_contents('./d24input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    // x00: 1
    if(preg_match("/^(\w+): (\d)$/", $line, $m)) {
      $values[$m[1]] = $m[2];
    }
    // ntg XOR fgs -> mjb
    if(preg_match("/->/", $line, $m)) {
      $m = explode(" ", $line);
      $expr[] = $m;
    }
  }
}

while(sizeof($expr) > 0) {
  foreach($expr as $key => $exp) {
    $val1 = $exp[0];
    $val2 = $exp[2];
    if(isset($values[$val1]) && isset($values[$val2])) {
      $gate = $exp[1];
      $val3 = $exp[4];
      switch($gate) {
        case "AND":
          $values[$val3] = $values[$val1] * $values[$val2];
          break;
        case "OR":
          $values[$val3] = $values[$val1] + $values[$val2] - $values[$val1] * $values[$val2];
          break;
        case "XOR":
          $values[$val3] = ($values[$val1] + $values[$val2]) % 2;
          break;
      }
      unset($expr[$key]);
    }
  }
}

$allzs = $values;
foreach($allzs as $key => $posz) {
  if(!preg_match("/z/", $key, $m)) {
    unset($allzs[$key]);
  }
}

print_r($allzs);

$sum = 0;
foreach($allzs as $key => $z) {
  $pos = (int) str_replace("z", "", $key);
  $sum += $z * pow(2, $pos);
}

print("$sum\n");
?>
