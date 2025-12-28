<?php

///////////////////////////////////////////////////

$input = file_get_contents('./d25input1.txt', true);

$item = 0;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    $items[$item][] = str_split($line);
  } else {
    $item++;
  }
}

foreach($items as $item) {
  $tmp = implode($item[0]);
  if($tmp == "#####") {
    $locks[] = $item;
  } else {
    $keys[] = $item;
  }
}

foreach($locks as $id => $lock) {
  // each pin, 5 of them
  for($i=0;$i<5;$i++) {
    // each pin element, 7 of them
    $pinh = 0;
    for($j=1;$j<7;$j++) {
      if($lock[$j][$i] == "#") {
        $pinh++;
      }
    }
    $locksig[$id][] = $pinh;
  }
}

foreach($keys as $id => $key) {
  // each pin, 5 of them
  for($i=0;$i<5;$i++) {
    // each pin element, 7 of them
    $pinh = 0;
    for($j=5;$j>0;$j--) {
      if($key[$j][$i] == "#") {
        $pinh++;
      }
    }
    $keysig[$id][] = $pinh;
  }
}

$lockkeymatch = 0;

foreach($locksig as $lock) {
  foreach($keysig as $key) {
    $tmp = array();
    for($i=0;$i<5;$i++) {
      $tmp[] = $lock[$i] + $key[$i];
    }
    if(max($tmp) <= 5) {
      $lockkeymatch++;
    }
  }
}

print("$lockkeymatch\n");

?>
