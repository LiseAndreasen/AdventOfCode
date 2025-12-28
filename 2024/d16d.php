<?php

// part 1
// 3rd approach
// true Dijkstra
// good data model

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

function find_short() {
  global $unvis, $maphei, $mapwid;
  $dist = 10000000;
  $newx = -1;
  $newy = -1;
  $newdir = "";
  for($x=0;$x<$mapwid;$x++) {
    if(!isset($unvis[$x])) {
      continue;
    }
    for($y=0;$y<$maphei;$y++) {
      if(!isset($unvis[$x][$y])) {
        continue;
      }
      foreach($unvis[$x][$y] as $dir => $val) {
        if($unvis[$x][$y][$dir][1] < $dist) {
          $newx = $x;
          $newy = $y;
          $newdir = $dir;
          $dist = $unvis[$x][$y][$dir][1];
        }
      }
    }
  }
  return array($newx, $newy, $newdir);
}

function isempty($map, $x, $y) {
  if($map[$y][$x] == ".") {
    return 1;
  } else {
    return 0;
  }
}

////////////////////////////////////////////////////////////////

$input = file_get_contents('./d16input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  //print("$line\n");
  if(strlen($line)>2) {
    $map[] = str_split($line);
  }
}
$maphei = sizeof($map);
$mapwid = sizeof($map[0]);

// Assign to every node a distance from start value.

// explode map - every cell turns into 4 cells
// $map[$y][$x]
// $dimap[$x][$y][dir][type,dist,neigh]
for($x=0;$x<$mapwid;$x++) {
  for($y=0;$y<$maphei;$y++) {
    $dimap[$x][$y]["^"] = array($map[$y][$x], 10000000, array());
    $dimap[$x][$y][">"] = array($map[$y][$x], 10000000, array());
    $dimap[$x][$y]["v"] = array($map[$y][$x], 10000000, array());
    $dimap[$x][$y]["<"] = array($map[$y][$x], 10000000, array());
  }
}

// neigh: (x, y, type, dist)

// vertical neigh
for($x=0;$x<$mapwid;$x++) {
  for($y=1;$y<$maphei-1;$y++) {
    // neigh above?
    if($dimap[$x][$y-1]["^"][0] != "#") {
      $dimap[$x][$y]["^"][2][] = array($x, $y-1, "^", 1);
    }
    // neigh below?
    if($dimap[$x][$y+1]["v"][0] != "#") {
      $dimap[$x][$y]["v"][2][] = array($x, $y+1, "v", 1);
    }
  }
}

// horizontal neigh
for($x=1;$x<$mapwid-1;$x++) {
  for($y=0;$y<$maphei;$y++) {
    // neigh to the right?
    if($dimap[$x+1][$y][">"][0] != "#") {
      $dimap[$x][$y][">"][2][] = array($x+1, $y, ">", 1);
    }
    // neigh to the left?
    if($dimap[$x-1][$y]["<"][0] != "#") {
      $dimap[$x][$y]["<"][2][] = array($x-1, $y, "<", 1);
    }
  }
}

// internal neigh
for($x=0;$x<$mapwid;$x++) {
  for($y=0;$y<$maphei;$y++) {
    $dimap[$x][$y]["^"][2][] = array($x, $y, ">", 1000);
    $dimap[$x][$y]["^"][2][] = array($x, $y, "<", 1000);
    $dimap[$x][$y][">"][2][] = array($x, $y, "^", 1000);
    $dimap[$x][$y][">"][2][] = array($x, $y, "v", 1000);
    $dimap[$x][$y]["v"][2][] = array($x, $y, "<", 1000);
    $dimap[$x][$y]["v"][2][] = array($x, $y, ">", 1000);
    $dimap[$x][$y]["<"][2][] = array($x, $y, "^", 1000);
    $dimap[$x][$y]["<"][2][] = array($x, $y, "v", 1000);
  }
}

// Create a set of all unvisited nodes: the unvisited set.
$unvis = $dimap;

// Assign to every node a distance from start value: for the starting node, it is zero.
$coords = find("S");
$x = $coords[0];
$y = $coords[1];
$unvis[$x][$y]["^"][1] = 1000;
$unvis[$x][$y][">"][1] = 0;
$unvis[$x][$y]["v"][1] = 1000;
$unvis[$x][$y]["<"][1] = 10000000;

$dist = 0;
// begin loop
//for($i=0;$i<2;$i++) {
while($dist<83500) {
  $coords = find_short();
  $x = $coords[0];
  $y = $coords[1];
  if($x == -1 || $y == -1) {
    break;
  }
  $dir = $coords[2];
  $dist = $unvis[$x][$y][$dir][1];

if($dist % 100 == 0) {
  echo "$dist ";
}

  if(sizeof($unvis[$x][$y][$dir][2]) > 0) {  
    // For the current node, consider all of its unvisited neighbors
    // and update their distances through the current node
    foreach($unvis[$x][$y][$dir][2] as $num => $neigh) {
      $neighx = $neigh[0];
      $neighy = $neigh[1];
      $neighdir = $neigh[2];
      $dist2neigh = $neigh[3];
      if(isset($unvis[$neighx][$neighy][$neighdir])) {
        $neighcurdist = $unvis[$neighx][$neighy][$neighdir][1];
        if($dist + $dist2neigh < $neighcurdist) {
          $unvis[$neighx][$neighy][$neighdir][1] = $dist + $dist2neigh;
        }
      } else {
        unset($unvis[$x][$y][$dir][2][$num]);
      }
    }
  }
  
  // After considering all of the current node's unvisited neighbors,
  // the current node is removed from the unvisited set.
  $dimap[$x][$y][$dir][1] = $dist;
  if($dimap[$x][$y][$dir][0] == "E") {
    break;
  }
  unset($unvis[$x][$y][$dir]);
// end loop
}

$coords = find("E");
$x = $coords[0];
$y = $coords[1];
print_r($dimap[$x][$y]);
?>
