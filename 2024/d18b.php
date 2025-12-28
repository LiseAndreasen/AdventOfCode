<?php

// test: 0-6, actual: 0-70
$actual = 1;
if($actual == 0) {
  // test
  $donemax = 12;
  $maxsz = 6;
  $inputfile = './d18input1.txt';
  $max_steps = 15;
} else {
  // actual
  $donemax = 1024;
  $maxsz = 70;
  $inputfile = './d18input2.txt';
  $max_steps = 2750; // played around with this value
}

function add_coord($now) {
  global $map, $coords, $newestcoord;
  $x = $coords[$now][0];
  $y = $coords[$now][1];
  $map[$x][$y] = "#";
  $newestcoord = "($x,$y)";
}

// array of coordinates in
function print_coords() {
  global $map, $coords, $mapwid, $maphei, $donemax;
  $done = 0;
  foreach($coords as $coord) {
    $x = $coord[0];
    $y = $coord[1];
    $map[$x][$y] = "#";
    $done++;
    if($done == $donemax) {
      break;
    }
  }
  print_map();
}

function print_map() {
  global $map, $mapwid, $maphei;
  for($y=-1;$y<=$maphei;$y++) {
    for($x=-1;$x<=$mapwid;$x++) {
      if(isset($map[$x][$y])) {
        echo $map[$x][$y];
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

$input = file_get_contents($inputfile, true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    preg_match_all('/-?\d+/', $line, $byte);
    $coords[] = $byte[0];
  }
}

$maphei = $maxsz + 1;
$mapwid = $maxsz + 1;

for($i=0;$i<$mapwid;$i++) {
  for($j=0;$j<$maphei;$j++) {
    $map[$j][$i] = ".";
  }
}
for($i=-1;$i<=$mapwid;$i++) {
  $map[-1][$i] = "#";
  $map[$maphei][$i] = "#";
}
for($j=-1;$j<=$maphei;$j++) {
  $map[$j][-1] = "#";
  $map[$j][$mapwid] = "#";
}
$map[0][0] = "S";
$map[$maxsz][$maxsz] = "E";

$xy = find("S");
$orgx = $xy[0];
$orgy = $xy[1];

function dijkstra() {
  global $map, $orgx, $orgy, $mapwid, $maphei;
  $x = $orgx;
  $y = $orgy;
  $wearedone = 0;

  // Create a set of all unvisited nodes: the unvisited set.
  // Assign to every node a distance from start.
  $unvis[] = array($orgx, $orgy, 0, ">");
  for($x=0;$x<$mapwid;$x++) {
    for($y=0;$y<$maphei;$y++) {
      if($map[$y][$x] == "." || $map[$y][$x] == "E") {
        $unvis[] = array($x, $y, 1000000, ".");
      }
    }
  }

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

    // For the current node, consider all of its unvisited neighbors and update their distances through the current node
    foreach($unvis as $key => $node) {
      $neighb = abs($x - $node[0]) + abs($y - $node[1]);
      if($neighb == 1) {
        //print("neighbour:\n");
        //print_r($node);
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
            $newdist = $dist + 1;
            if($newdist < $node[2]) {
              $unvis[$key][2] = $newdist;
              $unvis[$key][3] = "<";
            }
          }          
          if($dir == "v") {
            // i am headed down
            $newdist = $dist + 1;
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
            $newdist = $dist + 1;
            if($newdist < $node[2]) {
              $unvis[$key][2] = $newdist;
              $unvis[$key][3] = ">";
            }
          }          
          if($dir == "v") {
            // i am headed down
            $newdist = $dist + 1;
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
            $newdist = $dist + 1;
            if($newdist < $node[2]) {
              $unvis[$key][2] = $newdist;
              $unvis[$key][3] = "^";
            }
          }          
          if($dir == ">") {
            // i am headed right
            $newdist = $dist + 1;
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
            $newdist = $dist + 1;
            if($newdist < $node[2]) {
              $unvis[$key][2] = $newdist;
              $unvis[$key][3] = "v";
            }
          }          
          if($dir == ">") {
            // i am headed right
            $newdist = $dist + 1;
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
      return $dist;
      $wearedone = 1;
      continue;
    }
  }
  return 1000000;
}

$now = 0;
$newdist = 0;
$newestcoord = "";
for($i=0;$i<$max_steps;$i++) {
  add_coord($now);
  $now++;
}
print("After adding $max_steps bytes, ");
print("the shortest distance after adding 1 more is:\n");
while($newdist < 1000000) {
  add_coord($now);
  $now++;
  $newdist = dijkstra();
  echo $newdist." ";
}
print("\nOffending byte:\n");
echo $newestcoord."\n";

?>
