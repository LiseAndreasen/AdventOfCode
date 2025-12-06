<?php

// map in
function print_map() {
	global $map;
	
	// 20000 moves in 1 minute
//	usleep(500); // microseconds

	$tmp = "";
	$tmp .= "\n";
	$tmp .= "\n";
	foreach($map as $line) {
		$tmp .= "  ";
		$prev = "";
		foreach($line as $char) {
			if($prev != $char) {
				// code time
				if($prev != "") {
					// not first character
					$tmp .= "\033[0m";
				}
				switch($char) {
					case "#":
						$tmp .= "\033[47m"; // white
						break;
					case "O":
						$tmp .= "\033[43m"; // yellow
						break;
					case ".":
						$tmp .= "\033[40m"; // black
						break;
					case "@":
						$tmp .= "\033[41m"; // red
						break;
				} // switch
			}
			$tmp .= $char;
		}
		$tmp .= "\033[0m\n";
	}
	$tmp .= "\n";
//	$tmp .= "\n";
	system('clear');
	print "$tmp";
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
$maphei = sizeof($map);
$mapwid = sizeof($map[0]);
$moves = array_merge(...$moves);
$moves_sz = sizeof($moves);

// find robot
// find guard
for($i=0;$i<$maphei;$i++) {
  $j = array_search("@", $map[$i]);
  if($j) {
    //print("$i $j");
    $robx = $j;
    $roby = $i;
  }
}

for($i=-200;$i<0;$i++) {
	print_map();
	print("  $i of $moves_sz");
}

function movebox($x, $y, $dir) {
  global $map;
  switch ($dir) {
    case "<":
      if($map[$y][$x-1] == "#") {
        return 0;
      }
      if($map[$y][$x-1] == "O") {
        $tmp = movebox($x-1, $y, $dir);
        if($tmp == 1) {
          $map[$y][$x-2] = "O";
          $map[$y][$x-1] = ".";
        }
        return $tmp;
      } else {
        return 1;
      }
      break;
    case "v":
      if($map[$y+1][$x] == "#") {
        return 0;
      }
      if($map[$y+1][$x] == "O") {
        $tmp = movebox($x, $y+1, $dir);
        if($tmp == 1) {
          $map[$y+2][$x] = "O";
          $map[$y+1][$x] = ".";
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
      if($map[$y][$x+1] == "O") {
        $tmp = movebox($x+1, $y, $dir);
        if($tmp == 1) {
          $map[$y][$x+2] = "O";
          $map[$y][$x+1] = ".";
        }
        return $tmp;
      } else {
        return 1;
      }
      break;
    case "^":
      if($map[$y-1][$x] == "#") {
        return 0;
      }
      if($map[$y-1][$x] == "O") {
        $tmp = movebox($x, $y-1, $dir);
        if($tmp == 1) {
          $map[$y-2][$x] = "O";
          $map[$y-1][$x] = ".";
        }
        return $tmp;
      } else {
        return 1;
      }
      break;
  }
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
  print_map();
  print("  $i of $moves_sz");
}

//print_map();

// calculate gps
$gps = 0;
for($j=0;$j<$maphei;$j++) {
  for($i=0;$i<$mapwid;$i++) {
    if($map[$j][$i] == "O") {
      $gps += 100 * $j + $i;
    }
  }
}
//echo "$gps\n";

sleep(5);

?>
