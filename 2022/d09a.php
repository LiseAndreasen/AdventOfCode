<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d09input2.txt', true);

$move["U"] = [ 0, -1];
$move["D"] = [ 0,  1];
$move["L"] = [-1,  0];
$move["R"] = [ 1,  0];

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
		    $data[] = explode(" ",$line);
		}
	}
	return $data;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
//print_r($data);

// coordinates of head and tail, arbitrarily chosen 
$xh = 0;
$yh = 0;
$xt = 0;
$yt = 0;

// where has the tail been?
$tails[$xt][$yt] = 1;

//printf("head %d %d      ", $xh, $yh);
//printf("tail %d %d\n", $xt, $yt);
foreach ($data as $directions) {
    [$dir, $moves] = $directions;
    for($i=0;$i<$moves;$i++) {
        $xh += $move[$dir][0];
        $yh += $move[$dir][1];
        // distance from head to tail
        $x_diff = abs($xt - $xh);
        $y_diff = abs($yt - $yh);
        if(max($x_diff, $y_diff) == 2) {
            // tail will move
            if($x_diff == 2) {
                if($xt < $xh) {
                    $xt++;
                } else {
                    $xt--;
                }
                $yt = $yh;
            } else {
                if($yt < $yh) {
                    $yt++;
                } else {
                    $yt--;
                }
                $xt = $xh;
            }
        } // move tail?
        $tails[$xt][$yt] = 1;
//printf("head %d %d      ", $xh, $yh);
//printf("tail %d %d\n", $xt, $yt);
    } // doing the moves
}

//print_r($tails);

$tails_flat = array_merge(...$tails);
$no = sizeof($tails_flat);

printf("Result 1: %d\n", $no);

?>
