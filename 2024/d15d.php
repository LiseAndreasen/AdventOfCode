<?php
// map in
function print_map() {
  global $map, $mapwid, $maphei;
  for($j=0;$j<$maphei;$j++) {
    for($i=0;$i<$mapwid;$i++) {
      if(isset($map[$j][$j])) {
        echo $map[$j][$i];
      } else {
        echo ".";
      }
    }
    echo "\n";
  }
  echo "\n";
}


$input = file_get_contents('./d15input1.txt', true);

$map = array();
$phase = 1;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line) < 3) {
    $phase = 2;
  } else {
    if($phase == 1) {
      $map[] = str_split($line);
    } else {
      $moves[] = str_split($line);
    }
  }
}

// make map twice the width
foreach($map as $row) {
  foreach($row as $cell) {
    switch($cell) {
      case "#":
        $r2[] = $cell;
        $r2[] = $cell;
        break;
      case "@":
        $r2[] = $cell;
        $r2[] = ".";
        break;
      case "O":
        $r2[] = "[";
        $r2[] = "]";
        break;
      case ".":
        $r2[] = $cell;
        $r2[] = $cell;
        break;
    }
  }
  $m2[] = $r2;
  $r2 = array();
}
$map = $m2;

$maphei = sizeof($map);
$mapwid = sizeof($map[0]);
$moves = array_merge(...$moves);

// find robot
for($i=0;$i<$maphei;$i++) {
  $j = array_search("@", $map[$i]);
  if($j) {
    //print("$i $j");
    $robx = $j;
    $roby = $i;
  }
}
print_map();

function moveboxupdown($x, $y, $dir, $mywid) {

  global $map;
  // i am on (x,y) and i am mywid wide, going in dir direction
  // if there is a box in front of me, move it, if possible
  // note, adjacent boxes may be moved partially and then rolled back
  // if i am a box, (x,y) is my left part
  
  // first, check for wall
  if($dir == "^" && $map[$y-1][$x] == "#") {
    // wall, abort
    return 0;
  }
  if($dir == "^" && $mywid == 2 && $map[$y-1][$x+1] == "#") {
    // wall, abort
    return 0;
  }
  if($dir == "v" && $map[$y+1][$x] == "#") {
    // wall, abort
    return 0;
  }
  if($dir == "v" && $mywid == 2 && $map[$y+1][$x+1] == "#") {
    // wall, abort
    return 0;
  }

  // next check for further boxes, and move them!
  if($dir == "^" && $map[$y-1][$x-1] == "[") {
    $tmp = moveboxupdown($x-1, $y-1, $dir, 2);
    if($tmp == 0) {
      return 0;
    }
  }
  if($dir == "^" && $map[$y-1][$x] == "[") {
    $tmp = moveboxupdown($x, $y-1, $dir, 2);
    if($tmp == 0) {
      return 0;
    }
  }
  if($dir == "^" && $mywid == 2 && $map[$y-1][$x+1] == "[") {
    $tmp = moveboxupdown($x+1, $y-1, $dir, 2);
    if($tmp == 0) {
      return 0;
    }
  }
  if($dir == "v" && $map[$y+1][$x-1] == "[") {
    $tmp = moveboxupdown($x-1, $y+1, $dir, 2);
    if($tmp == 0) {
      return 0;
    }
  }
  if($dir == "v" && $map[$y+1][$x] == "[") {
    $tmp = moveboxupdown($x, $y+1, $dir, 2);
    if($tmp == 0) {
      return 0;
    }
  }
  if($dir == "v" && $mywid == 2 && $map[$y+1][$x+1] == "[") {
    $tmp = moveboxupdown($x+1, $y+1, $dir, 2);
    if($tmp == 0) {
      return 0;
    }
  }
  
  // if i reached this point, i can move a box
  if($dir == "^" && $mywid == 2) {
    $map[$y-1][$x] = "[";
    $map[$y-1][$x+1] = "]";
    $map[$y][$x] = ".";
    $map[$y][$x+1] = ".";
  }
  if($dir == "v" && $mywid == 2) {
    $map[$y+1][$x] = "[";
    $map[$y+1][$x+1] = "]";
    $map[$y][$x] = ".";
    $map[$y][$x+1] = ".";
  }

  return 1;
}

function moveboxleftright($x, $y, $dir) {
  global $map;
  switch ($dir) {
    case "<":
      if($map[$y][$x-1] == "#") {
        return 0;
      }
      if($map[$y][$x-1] == "]") {
        $tmp = moveboxleftright($x-2, $y, $dir);
        if($tmp == 1) {
          $map[$y][$x-3] = "[";
          $map[$y][$x-2] = "]";
          $map[$y][$x-1] = ".";
        }
        return $tmp;
      } else {
        return 1;
      }
      break;
    case ">":
      if($map[$y][$x+1] == "#") {
        return 0;
      }
      if($map[$y][$x+1] == "[") {
        $tmp = movebox($x+2, $y, $dir);
        if($tmp == 1) {
          $map[$y][$x+3] = "]";
          $map[$y][$x+2] = "[";
          $map[$y][$x+1] = ".";
        }
        return $tmp;
      } else {
        return 1;
      }
      break;
  }
}

function movebox($x, $y, $dir) {
  global $map;
  $backup = $map;
  if($dir == "^" || $dir == "v") {
    $tmp = moveboxupdown($x, $y, $dir, 1);
  } else {
    $tmp = moveboxleftright($x, $y, $dir);
  }
  if($tmp == 0) {
    $map = $backup;
  }
  return $tmp;
}

// walk the robot
for($i=0;$i<sizeof($moves);$i++) {
  if(movebox($robx, $roby, $moves[$i])) {
    if($moves[$i] == "^") {
      if($map[$roby-1][$robx] != "#") {
        $map[$roby-1][$robx] = "@";
        $map[$roby][$robx] = ".";
        $roby--;
      }
    }
    if($moves[$i] == ">") {
      if($map[$roby][$robx+1] != "#") {
        $map[$roby][$robx+1] = "@";
        $map[$roby][$robx] = ".";
        $robx++;
      }
    }
    if($moves[$i] == "v") {
      if($map[$roby+1][$robx] != "#") {
        $map[$roby+1][$robx] = "@";
        $map[$roby][$robx] = ".";
        $roby++;
      }
    }
    if($moves[$i] == "<") {
      if($map[$roby][$robx-1] != "#") {
        $map[$roby][$robx-1] = "@";
        $map[$roby][$robx] = ".";
        $robx--;
      }
    }
  }
}

print_map();

// calculate gps
$gps = 0;
for($j=0;$j<$maphei;$j++) {
  for($i=0;$i<$mapwid;$i++) {
    if($map[$j][$i] == "[") {
      $gps += 100 * $j + $i;
    }
  }
}
echo "$gps\n";
?>
