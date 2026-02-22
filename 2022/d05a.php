<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d05input2.txt', true);
$part = 2;

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	$crates = true;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	    if($crates) {
    		if(strlen($line)>2) {
    			$data1[] = str_split($line);
    		} else {
    		    $crates = false;
    		}
	    } else {
	        preg_match('#move (.*) from (.*) to (.*)#', $line, $m);
	        [$all, $d1, $d2, $d3] = $m;
	        $data2[] = [$d1, $d2, $d3];
	    }
	}
	$data1 = pivot($data1);
	return [$data1, $data2];
}

// change (y, x) map to (x, y) map
function pivot($map) {
    $map_height = sizeof($map);
    $map_width = sizeof($map[0]);
    for($j=0;$j<$map_height;$j++) {
        for($i=0;$i<$map_width;$i++) {
            $map2[$i][$j] = $map[$j][$i];
        }
    }
    return $map2;
}

///////////////////////////////////////////////////////////////////////////
// main program

[$data1, $data2] = get_input($input);

// create a structure for the stacks
foreach($data1 as $i => $stack) {
    if($i % 4 == 1) { // only the actual stacks
        $stack_no = array_pop($stack);
        while($stack[0] == " ") { // remove empty space
            array_shift($stack);
        }
        // top crate in right most position
        $stacks[$stack_no] = array_reverse($stack);
    }
}

// move the crates around
foreach($data2 as $moves) {
    [$move, $from, $to] = $moves;
    $tmp = [];
    for($i=0;$i<$move;$i++) {
        $tmp[] = array_pop($stacks[$from]);
    }
    for($i=0;$i<$move;$i++) {
        if($part == 1) {
            // add newest first
            $stacks[$to][] = array_shift($tmp);
        } else {
            // add oldest first
            $stacks[$to][] = array_pop($tmp);
        }
    }
}

// look at the top crates
$top_crates = "";
foreach($stacks as $stack) {
    $crate = array_pop($stack);
    $top_crates .= $crate;
}

printf("Result %d: %s\n", $part, $top_crates);

?>
