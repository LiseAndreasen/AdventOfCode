<?php

function dig($design, $level) {
  // towels are at most 8 characters long
  global $towels, $dug;
  if(isset($dug[$design])) {
    return $dug[$design];
  }
  $hit = 0;
  for($i=8;$i>0;$i--) {
    // if the beginning of this design is towel
    if(in_array(substr($design, 0, $i), $towels)) {
      if(strlen($design) == $i) {
        $dug[$design] = 1;
        $hit += 1;
      }
      $tmphit = dig(substr($design, $i), $level+1);
      if($tmphit > 0) {
        $dug[$design] = $tmphit;
        $hit += $tmphit;
      }
    }
  }
  $dug[$design] = $hit;
  return $hit;
}

///////////////////////////////////////////////////////////

$input = file_get_contents('./d19input1.txt', true);

$phase = 1;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    if($phase == 1) {
      $towels = explode(", ", $line);
    } else {
      $designs[] = $line;
    }
  } else {
    $phase = 2;
  }
}

$hits = 0;
foreach($designs as $design) {
  $tmphit = dig($design, 0);
  if($tmphit > 0) {
    // part 1:
    //$hits++;
    
    // part 2:
    $hits += $tmphit;
  }
}
echo $hits."\n";
