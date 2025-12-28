<?php

// part 1
// 2nd approach
// closer to Dijkstra

function print_map() {
  global $map, $mapwid, $maphei;
  for($y=0;$y<$maphei;$y++) {
    for($x=0;$x<$mapwid;$x++) {
      if(isset($map[$y][$x])) {
        echo $map[$y][$x];
      } else {
        echo ".";
      }
    }
    echo "\n";
  }
  echo "\n";
}

function find($char) {
  global $map, $mapwid, $maphei;
  for($x=0;$x<$mapwid;$x++) {
    for($y=0;$y<$maphei;$y++) {
      if($map[$y][$x] == $char) {
        return array($x,$y);
      }
    }
  }
}

////////////////////////////////////////////////////////////////

$input = file_get_contents('./d16input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    $map[] = str_split($line);
  }
}
$maphei = sizeof($map);
$mapwid = sizeof($map[0]);

$coords = find("S");
$x = $coords[0];
$y = $coords[1];
$unvis[] = array($x, $y, 0, ">");

// Create a set of all unvisited nodes: the unvisited set.
// Assign to every node a distance from start.
for($x=0;$x<$mapwid;$x++) {
  for($y=0;$y<$maphei;$y++) {
    if($map[$y][$x] == "." || $map[$y][$x] == "E") {
      $unvis[] = array($x, $y, 1000000, ".");
    }
  }
}

$wearedone = 0;
while($wearedone == 0) {
  //From the unvisited set, select the current node to be the one with the smallest (finite) distance
  $dist = 1000000;
  foreach($unvis as $key => $node) {
    if($node[2] < $dist) {
      $dist = $node[2];
      $x = $node[0];
      $y = $node[1];
      $dir = $node[3];
      $mykey = $key;
    }
  }
  
  // shortest path: 83432
  if($dist > 83435) {
    $wearedone = 1;
    continue;
  }

  //print("$dist $x $y $dir\n");

  // For the current node, consider all of its unvisited neighbors and update their distances through the current node
  foreach($unvis as $key => $node) {
    $neighb = abs($x - $node[0]) + abs($y - $node[1]);
    if($neighb == 1) {
      if($x - $node[0] == 1) {
        // neighbour to the left
        if($dir == "<") {
          // i am headed left
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "<";
          }
        }
        if($dir == "^") {
          // i am headed up
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "<";
          }
        }          
        if($dir == "v") {
          // i am headed down
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "<";
          }
        }
      }
      if($x - $node[0] == -1) {
        // neighbour to the right
        if($dir == ">") {
          // i am headed right
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = ">";
          }
        }
        if($dir == "^") {
          // i am headed up
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = ">";
          }
        }          
        if($dir == "v") {
          // i am headed down
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = ">";
          }
        }
      }
      if($y - $node[1] == 1) {
        // neighbour above
        if($dir == "^") {
          // i am headed up
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "^";
          }
        }
        if($dir == "<") {
          // i am headed left
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "^";
          }
        }          
        if($dir == ">") {
          // i am headed right
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "^";
          }
        }
      }
      if($y - $node[1] == -1) {
        // neighbour below
        if($dir == "v") {
          // i am headed down
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "v";
          }
        }
        if($dir == "<") {
          // i am headed left
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "v";
          }
        }          
        if($dir == ">") {
          // i am headed right
          $newdist = $dist + 1 + 1000;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = "v";
          }
        }
      }
    }
  }
  
  // the current node is removed from the unvisited set
  $visit[] = array($x, $y, $dist, $dir);
  unset($unvis[$mykey]);
  if($map[$y][$x] == "E") {
    print_map();
    print("shortest path: $dist\n");
    $wearedone = 1;
    continue;
  }
}
?>
