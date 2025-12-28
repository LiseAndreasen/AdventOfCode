<?php

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

$input = file_get_contents('./d20input1.txt', true);
// limit: how many steps saved, before a route counts
// part 1, test: 2, actual: 100
// part 2: test: 50, actual: 100
$limit = 2;
// cheat: how many steps in a row can ignore walls
// part 1: 2
// testa: 4, testb: 20, actual: 20
$cheat = 2;

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    $map[] = str_split($line);
  }
}

$maphei = sizeof($map);
$mapwid = sizeof($map[0]);

$coords = find("S");
$orgx = $coords[0];
$orgy = $coords[1];

function dijkstra($map) {
  global $orgx, $orgy, $maphei, $mapwid, $visit;
  
  $unvis[] = array($orgx, $orgy, 0, array());

  // Create a set of all unvisited nodes: the unvisited set.
  // Assign to every node a distance from start.
  for($x=0;$x<$mapwid;$x++) {
    for($y=0;$y<$maphei;$y++) {
      if($map[$y][$x] == "." || $map[$y][$x] == "E") {
        $unvis[] = array($x, $y, 1000000, array());
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
        $mykey = $key;
      }
    }
    
    // For the current node, consider all of its unvisited neighbors and update their distances through the current node
    foreach($unvis as $key => $node) {
      $neighb = abs($x - $node[0]) + abs($y - $node[1]);
      if($neighb == 1) {
        //print("neighbour:\n");
        //print_r($node);
        if($x - $node[0] == 1) {
          // neighbour to the left
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = array($x, $y);
          }
        }
        if($x - $node[0] == -1) {
          // neighbour to the right
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = array($x, $y);
          }
        }
        if($y - $node[1] == 1) {
          // neighbour above
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = array($x, $y);
          }
        }
        if($y - $node[1] == -1) {
          // neighbour below
          $newdist = $dist + 1;
          if($newdist < $node[2]) {
            $unvis[$key][2] = $newdist;
            $unvis[$key][3] = array($x, $y);
          }
        }
      }
    }
    
    // the current node is removed from the unvisited set
    $visit[$x][$y][] = $unvis[$mykey];
    unset($unvis[$mykey]);
    if($map[$y][$x] == "E") {
      return $dist;
    }
  }
}

$best = dijkstra($map);

for($i=1;$i<$maphei-1;$i++) {
  for($j=1;$j<$mapwid-1;$j++) {
    // if i am a free cell, no. 0
    // my cheat begins
    
    // my destination will be at most n cells away
    // but still has to be on the map
    
    // check all free cells within n steps distance
        
    // always assume efficient routes
    // as meandering ones will be longer
    if($map[$i][$j] == "#") {
      continue;
    }
    for($a=max($i-$cheat,0);$a<=min($i+$cheat,$maphei-1);$a++) {
      for($b=max($j-$cheat,0);$b<=min($j+$cheat,$mapwid-1);$b++) {
        $ytmp = abs($a-$i);
        $xtmp = abs($b-$j);
        $alltmp = $xtmp + $ytmp;
        if(1<$alltmp && $alltmp<=$cheat) {
          // we have a candidate!
          if($map[$a][$b] != "#") {
            $newdist = $best - ($visit[$j][$i][0][2] - $visit[$b][$a][0][2]) + $alltmp;
            if(isset($alldist[$newdist])) {
              $alldist[$newdist]++;
            } else {
              $alldist[$newdist] = 1;
            }
          }
        } 
      }
    } // end big loop
  }
}

$cheats = 0;
for($i=0;$i<10000;$i+=2) {
  if(!isset($alldist[$i])) {
    $alldist[$i] = 0;
  }
}
for($i=$best-$limit;$i>0;$i-=2) {
  $lgt = $i;
  $no = $alldist[$i];
  if($no > 0) {
    print("There are $no cheats that save ".$best-$lgt." picoseconds.\n");
  }
  if($lgt <= $best - $limit) {
    $cheats += $no;
  }
}
echo $cheats."\n";

