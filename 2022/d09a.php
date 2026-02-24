<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d09input1.txt', true);

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

function print_map($map, $xmin, $xmax, $ymin, $ymax) {
    for($j=$ymin;$j<=$ymax;$j++) {
        for($i=$xmin;$i<=$xmax;$i++) {
            if(isset($map[$i][$j])) {
                echo "#";
            } else {
                echo ".";
            }
        }
        echo "\n";
    }
    for($i=$xmin;$i<=$xmax;$i++) {
        echo "=";
    }
    echo "\n";
}

function move_rope($data, $l) {
    global $move;
    
    // coordinates of head and tail, arbitrarily chosen
    // length of tail: l
    $x[0] = 0;
    $y[0] = 0;
    for($i=1;$i<=$l;$i++) {
        $x[$i] = 0;
        $y[$i] = 0;
    }
    
    // where has the tail been?
    $tails[$x[$l]][$y[$l]] = 1;
    
    //printf("head %d %d      ", $x[0], $y[0]);
    //printf("tail %d %d\n", $x[1], $y[1]);
    foreach ($data as $directions) {
        [$dir, $moves] = $directions;
        for($i=0;$i<$moves;$i++) {
            // move the head
            $x[0] += $move[$dir][0];
            $y[0] += $move[$dir][1];
            for($j=1;$j<=$l;$j++) {
                // distance from this knot to previous knot
                $x_diff = abs($x[$j] - $x[$j-1]);
                $y_diff = abs($y[$j] - $y[$j-1]);
                if(max($x_diff, $y_diff) == 2) {
                    // tail will move
                    if($x_diff == 2) {
                        if($x[$j] < $x[$j-1]) {
                            $x[$j]++;
                        } else {
                            $x[$j]--;
                        }
                        $y[$j] = $y[$j-1];
                    } else {
                        if($y[$j] < $y[$j-1]) {
                            $y[$j]++;
                        } else {
                            $y[$j]--;
                        }
                        $x[$j] = $x[$j-1];
                    }
                } // move tail?
            }
            $tails[$x[$l]][$y[$l]] = 1;
//            printf("head %d %d      ", $x[0], $y[0]);
//            printf("tail %d %d\n", $x[$l], $y[$l]);
        } // doing the moves
    }

    $xmin = min(array_keys($tails));
    $xmax = max(array_keys($tails));
    $ymin = 0;
    $ymax = 0;
    foreach ($tails as $column) {
        if(min(array_keys($column)) < $ymin) {
            $ymin = min(array_keys($column));
        }
        if($ymax < max(array_keys($column))) {
            $ymax = max(array_keys($column));
        }
    }
    print_map($tails, $xmin, $xmax, $ymin, $ymax);
    
    $tails_flat = array_merge(...$tails);
    $no = sizeof($tails_flat);
    return $no;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
//print_r($data);

$no = move_rope($data, 1);

printf("Result 1: %d\n", $no);

$no = move_rope($data, 9);

printf("Result 2: %d\n", $no);

?>
