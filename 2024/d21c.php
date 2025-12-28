<?php

$trans1["0"]["0"] = array("A");
$trans1["0"]["1"] = array("^<A"); // not the other way around!
$trans1["0"]["2"] = array("^A");
$trans1["0"]["3"] = array("^>A", ">^A");
$trans1["0"]["4"] = array("^^<A", "^<^"); // not left first!
$trans1["0"]["5"] = array("^^A");
$trans1["0"]["6"] = array("^^>A", "^>^A", ">^^");
$trans1["0"]["7"] = array("^^^<A", "^^<^A", "^<^^A"); // not left first!
$trans1["0"]["8"] = array("^^^A");
$trans1["0"]["9"] = array("^^^>A", "^^>^", "^>^^", ">^^^");
$trans1["0"]["A"] = array(">A");
$trans1["1"]["0"] = array(">vA"); // not the other way around!
$trans1["1"]["1"] = array("A");
$trans1["1"]["2"] = array(">A");
$trans1["1"]["3"] = array(">>A");
$trans1["1"]["4"] = array("^A");
$trans1["1"]["5"] = array("^>A", ">^A");
$trans1["1"]["6"] = array("^>>A", ">^>A", ">>^A");
$trans1["1"]["7"] = array("^^A");
$trans1["1"]["8"] = array("^^>A", "^>^A", ">^^A");
$trans1["1"]["9"] = array("^^>>A", "^>^>A", ">^^>A", "^>>^A", ">^>^A", ">>^^A");
$trans1["1"]["A"] = array(">>vA", ">v>A"); // not down first!
$trans1["2"]["0"] = array("vA");
$trans1["2"]["1"] = array("<A");
$trans1["2"]["2"] = array("A");
$trans1["2"]["3"] = array(">A");
$trans1["2"]["4"] = array("^<A", "<^A");
$trans1["2"]["5"] = array("^A");
$trans1["2"]["6"] = array("^>A", ">^A");
$trans1["2"]["7"] = array("^^<A", "^<^A", "<^^A");
$trans1["2"]["8"] = array("^^A");
$trans1["2"]["9"] = array("^^>A", "^>^A", ">^^A");
$trans1["2"]["A"] = array("v>A", ">vA");
$trans1["3"]["0"] = array("<vA", "v<A");
$trans1["3"]["1"] = array("<<A");
$trans1["3"]["2"] = array("<A");
$trans1["3"]["3"] = array("A");
$trans1["3"]["4"] = array("^<<A", "<^<A", "<<^A");
$trans1["3"]["5"] = array("^<A", "<^A");
$trans1["3"]["6"] = array("^A");
$trans1["3"]["7"] = array("^^<<A", "^<^<A", "<^^<A", "^<<^A", "<^<^A", "<<^^A"); // order matters
$trans1["3"]["8"] = array("^^<A", "^<^A", "<^^A");
$trans1["3"]["9"] = array("^^A");
$trans1["3"]["A"] = array("vA");
$trans1["4"]["0"] = array(">vvA", "v>vA"); // not right last!
$trans1["4"]["1"] = array("vA");
$trans1["4"]["2"] = array("v>A", ">vA");
$trans1["4"]["3"] = array("v>>A", ">v>A", ">>vA");
$trans1["4"]["4"] = array("A");
$trans1["4"]["5"] = array(">A");
$trans1["4"]["6"] = array(">>A");
$trans1["4"]["7"] = array("^A");
$trans1["4"]["8"] = array("^>A", ">^A");
$trans1["4"]["9"] = array("^>>A", ">^>A", ">>^A");
$trans1["4"]["A"] = array(">>vvA", ">v>vA", "v>>vA", ">vv>A", "v>v>A"); // not 2 x down first!
$trans1["5"]["0"] = array("vvA");
$trans1["5"]["1"] = array("v<A", "<vA");
$trans1["5"]["2"] = array("vA");
$trans1["5"]["3"] = array("v>A", ">vA");
$trans1["5"]["4"] = array("<A");
$trans1["5"]["5"] = array("A");
$trans1["5"]["6"] = array(">A");
$trans1["5"]["7"] = array("^<A", "<^A");
$trans1["5"]["8"] = array("^A");
$trans1["5"]["9"] = array("^>A", ">^A");
$trans1["5"]["A"] = array("vv>A", "v>vA", ">vvA");
$trans1["6"]["0"] = array("vv<A", "v<vA", "<vvA");
$trans1["6"]["1"] = array("v<<A", "<v<A", "<<vA");
$trans1["6"]["2"] = array("v<A", "<vA");
$trans1["6"]["3"] = array("vA");
$trans1["6"]["4"] = array("<<A");
$trans1["6"]["5"] = array("<A");
$trans1["6"]["6"] = array("A");
$trans1["6"]["7"] = array("^<<A", "<^<A", "<<^A");
$trans1["6"]["8"] = array("^<A", "<^A");
$trans1["6"]["9"] = array("^A");
$trans1["6"]["A"] = array("vvA");
$trans1["7"]["0"] = array(">vvvA", "v>vvA", "vv>vA"); // not right last!
$trans1["7"]["1"] = array("vvA");
$trans1["7"]["2"] = array("vv>A", "v>vA", ">vvA");
$trans1["7"]["3"] = array("vv>>A", "v>v>A", ">vv>A", "v>>vA", ">v>vA", ">>vvA");
$trans1["7"]["4"] = array("vA");
$trans1["7"]["5"] = array("v>A", ">vA");
$trans1["7"]["6"] = array("v>>A", ">v>A", ">>vA");
$trans1["7"]["7"] = array("A");
$trans1["7"]["8"] = array(">A");
$trans1["7"]["9"] = array(">>A");
$trans1["7"]["A"] = array(">>vvvA", ">v>vvA", ">vv>vA", ">vvv>A", "v>>vvA", "v>v>vA", "v>vv>A", "vv>>vA", "vv>v>A"); // not 3 x down first!
$trans1["8"]["0"] = array("vvvA");
$trans1["8"]["1"] = array("vv<A", "v<vA", "<vvA");
$trans1["8"]["2"] = array("vvA");
$trans1["8"]["3"] = array("vv>A", "v>vA", ">vvA");
$trans1["8"]["4"] = array("v<A", "<vA");
$trans1["8"]["5"] = array("vA");
$trans1["8"]["6"] = array("v>A", ">vA");
$trans1["8"]["7"] = array("<A");
$trans1["8"]["8"] = array("A");
$trans1["8"]["9"] = array(">A");
$trans1["8"]["A"] = array(">vvvA", "v>vvA", "vv>vA", "vvv>A");
$trans1["9"]["0"] = array("vvv<A", "vv<vA", "v<vvA", "<vvvA");
$trans1["9"]["1"] = array("vv<<A", "v<v<A", "<vv<A", "v<<vA", "<v<vA", "<<vvA");
$trans1["9"]["2"] = array("vv<A", "v<vA", "<vvA");
$trans1["9"]["3"] = array("vvA");
$trans1["9"]["4"] = array("v<<A", "<v<A", "<<vA");
$trans1["9"]["5"] = array("v<A", "<vA");
$trans1["9"]["6"] = array("vA");
$trans1["9"]["7"] = array("<<A");
$trans1["9"]["8"] = array("<A");
$trans1["9"]["9"] = array("A");
$trans1["9"]["A"] = array("vvvA");
$trans1["A"]["0"] = array("<A");
$trans1["A"]["1"] = array("^<<A", "<^<A"); // not up last!
$trans1["A"]["2"] = array("^<A", "<^A");
$trans1["A"]["3"] = array("^A");
$trans1["A"]["4"] = array("^^<<A", "^<^<A", "<^^<A", "^<<^A", "<^<^A"); // not 2 x left first!
$trans1["A"]["5"] = array("^^<A", "^<^A", "<^^A");
$trans1["A"]["6"] = array("^^A");
$trans1["A"]["7"] = array("^^^<<A", "^^<^<A", "^<^^<A", "<^^^<A", "^^<<^A", "^<^<^A", "<^^<^A", "^<<^^A", "<^<^^A"); // not 2 x left first!
$trans1["A"]["8"] = array("^^^<A", "^^<^A", "^<^^A", "<^^^A");
$trans1["A"]["9"] = array("^^^A");
$trans1["A"]["A"] = array("A");

$trans2["<"]["<"] = array("A");
$trans2["<"]["^"] = array(">^A");
$trans2["<"][">"] = array(">>A");
$trans2["<"]["v"] = array(">A");
$trans2["<"]["A"] = array(">>^A", ">^>A");
$trans2["^"]["<"] = array("v<A");
$trans2["^"]["^"] = array("A");
$trans2["^"][">"] = array("v>A", ">vA");
$trans2["^"]["v"] = array("vA");
$trans2["^"]["A"] = array(">A");
$trans2[">"]["<"] = array("<<A");
$trans2[">"]["^"] = array("<^A", "^<A");
$trans2[">"][">"] = array("A");
$trans2[">"]["v"] = array("<A");
$trans2[">"]["A"] = array("^A");
$trans2["v"]["<"] = array("<A");
$trans2["v"]["^"] = array("^A");
$trans2["v"][">"] = array(">A");
$trans2["v"]["v"] = array("A");
$trans2["v"]["A"] = array("^>A", ">^A");
$trans2["A"]["<"] = array("v<<A", "<v<A");
$trans2["A"]["^"] = array("<A");
$trans2["A"][">"] = array("vA");
$trans2["A"]["v"] = array("v<A", "<vA");
$trans2["A"]["A"] = array("A");

///////////////////////////////////////////////////////////

$input = file_get_contents('./d21input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  if(strlen($line)>2) {
    preg_match('/\d+/', $line, $tmp);
    $codesorg[] = $tmp[0];
    $codes[] = str_split($line);
  }
}

function trs3($code3) {
  global $trans2, $memo2;
  
  // to directions the last time
  $numnext = $code3[0];
  $code0 = $trans2["A"][$numnext][0];
  $codelgt = sizeof($code3);
  for($i=0;$i<$codelgt-1;$i++) {
    $num = $code3[$i];
    $numnext = $code3[$i+1];
    $code0 .= $trans2[$num][$numnext][0];
  }
  //$memo2[$tmpcode] = $code0;
  return $code0;
}

function trs2($code2, $level) {

  global $trans2, $memo;
  
  if($level == 1) { // n+2 pads with arrows
    return trs3($code2);
  }
  
  // to directions again
  $numnext = $code2[0];
  
  $perms = $trans2["A"][$numnext];
  foreach($perms as $key => $perm) {
    $postpermtest = trs2(str_split($perm), $level + 1);
    if($key == 0 || $postlgt > strlen($postpermtest)) {
      $postlgt = strlen($postpermtest);
      $postperm = $postpermtest;
    }
  }
  $code0 = $postperm;

  $codelgt = sizeof($code2);
  for($i=0;$i<$codelgt-1;$i++) {
    $num = $code2[$i];
    $numnext = $code2[$i+1];
    $perms = $trans2[$num][$numnext];
    foreach($perms as $key => $perm) {
      $postpermtest = trs2(str_split($perm), $level + 1);
      if($key == 0 || $postlgt > strlen($postpermtest)) {
        $postlgt = strlen($postpermtest);
        $postperm = $postpermtest;
      }
    }
    $code0 .= $postperm;
  }

  return $code0;
}

function trs1($code1) {
  global $trans1;
  // from numbers to directions
  $numnext = $code1[0];

  $perms = $trans1["A"][$numnext];
  foreach($perms as $key => $perm) {
    $postpermtest = trs2(str_split($perm), 0);
    if($key == 0 || $postlgt > strlen($postpermtest)) {
      $postlgt = strlen($postpermtest);
      $postperm = $postpermtest;
    }
  }
  $code0 = $postperm;

  $codelgt = sizeof($code1);
  for($i=0;$i<$codelgt-1;$i++) {
    $num = $code1[$i];
    $numnext = $code1[$i+1];
    $perms = $trans1[$num][$numnext];
    foreach($perms as $key => $perm) {
      $postpermtest = trs2(str_split($perm), 0);
      if($key == 0 || $postlgt > strlen($postpermtest)) {
        $postlgt = strlen($postpermtest);
        $postperm = $postpermtest;
      }
    }
    $code0 .= $postperm;
  }

  return $code0;
}

$memo = array();
$memo2 = array();

$sum = 0;
// begin loop
foreach($codes as $key => $code1) {
  echo "Number: ".$codesorg[$key]."\n";
  $code0 = trs1($code1);
  echo "Code:   ".strlen($code0)."\n";

  $sum += strlen($code0) * $codesorg[$key];
// end loop
}

echo "\n".$sum."\n";

?>
