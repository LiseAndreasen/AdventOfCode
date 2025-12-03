<?php

$input = file_get_contents('./d12input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    $map[] = str_split($line);
  }
}
$mapx = sizeof($map);
$mapy = sizeof($map[0]);

// name possible areas
$names = array();
for($i=0;$i<sizeof($map);$i++) {
  for($j=0;$j<sizeof($map[$i]);$j++) {
    $names[$map[$i][$j]] = 1;
  }
}

// convert names
$names2 = array();
foreach($names as $key => $val) {
  $names2[] = $key;
}
$names = $names2;

// thin areas out
$areas = array();
for($k=0;$k<sizeof($names);$k++) {
  $lett = $names[$k];
  $tmp = $map;
  for($i=0;$i<$mapx;$i++) {
    for($j=0;$j<$mapy;$j++) {
      if($tmp[$i][$j] != $lett) {
        unset($tmp[$i][$j]);
      }
    }
  }
  $areas[$k] = $tmp;
}

function subtst($i, $j) {
  global $tmpb;
  //print_r($tmpb);
  if(isset($tmpb[$i-1][$j])) {
    if($tmpb[$i-1][$j] != "*") {
      $tmpb[$i-1][$j] = "*";
      subtst($i-1, $j);
    }
  }
  if(isset($tmpb[$i+1][$j])) {
    if($tmpb[$i+1][$j] != "*") {
      $tmpb[$i+1][$j] = "*";
      subtst($i+1, $j);
    }
  }
  if(isset($tmpb[$i][$j-1])) {
    if($tmpb[$i][$j-1] != "*") {
      $tmpb[$i][$j-1] = "*";
      subtst($i, $j-1);
    }
  }
  if(isset($tmpb[$i][$j+1])) {
    if($tmpb[$i][$j+1] != "*") {
      $tmpb[$i][$j+1] = "*";
      subtst($i, $j+1);
    }
  }
}

function atst() {
  global $tmp, $map, $tmpb, $key, $mapx, $mapy, $areas;

  // grab random beginning point
  $lett = "";
  for($i=0;$i<$mapx;$i++) {
    for($j=0;$j<$mapy;$j++) {
      if(isset($tmp[$i][$j])) {
        $lett = $tmp[$i][$j];
        $x = $i;
        $y = $j;
        break 2;
      }
    }
  }

  // can I reach all points from here?
  $tmpb = $tmp;
  $tmpb[$x][$y] = "*";
  subtst($x, $y);
  if(sizeof(array_count_values(array_merge(...$tmpb))) > 1) {
    //print("alert\n");
    $new1 = $tmpb;
    for($i=0;$i<$mapx;$i++) {
      for($j=0;$j<$mapy;$j++) {
        if(isset($new1[$i][$j])) {
          if($new1[$i][$j] == $lett) {
            unset($new1[$i][$j]);
          } else {
            $new1[$i][$j] = $lett;
          }
        }
      }
    }
    $new2 = $tmpb;
    for($i=0;$i<$mapx;$i++) {
      for($j=0;$j<$mapy;$j++) {
        if(isset($new2[$i][$j])) {
          if($new2[$i][$j] != $lett) {
            unset($new2[$i][$j]);
          }
        }
      }
    }
    $areas[$key] = $new1;
    $areas[] = $new2;
  }
}

// if areas aren't actually connected, split up
$areaz = 0;
while($areaz < sizeof($areas)) {
  $areaz = sizeof($areas);
  foreach($areas as $key => $area) {
    $tmp = $area;
    atst();
  }  
}

$sum = 0;
foreach($areas as $key => $area) {
  $sz1 = array_count_values(array_merge(...$area));
  $sz2 = array_pop($sz1);
  $per = 0;
  for($i=0;$i<$mapx;$i++) {
    for($j=0;$j<$mapy;$j++) {
      if(isset($area[$i][$j])) {
        if(!isset($area[$i-1][$j])) {
          $per++;
        }
        if(!isset($area[$i+1][$j])) {
          $per++;
        }
        if(!isset($area[$i][$j-1])) {
          $per++;
        }
        if(!isset($area[$i][$j+1])) {
          $per++;
        }
      }
    }
  }
  $sum += $sz2 * $per;
}  

print("$sum\n");
?>
