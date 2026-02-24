<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d08input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = str_split($line);
		}
	}
	return $data;
}

function print_map($map) {
    foreach($map[0] as $j => $cell) {
        foreach($map as $i => $col) {
            echo $map[$i][$j];
        }
        echo "\n";
    }
    for($i=0;$i<sizeof($map);$i++) {
        echo "=";
    }
    echo "\n";
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

function find_visible_trees($data) {
    $data_sz = sizeof($data);
    
    $visible_col = array_fill(0, $data_sz, "i"); // invisible
    $visible = array_fill(0, $data_sz, $visible_col);
    
    for($i=0;$i<=$data_sz-1;$i++) {
        $high = -1;
        for($j=0;$j<=$data_sz-1;$j++) {
            if($high < $data[$i][$j]) {
                $visible[$i][$j] = "v"; // visible
                $high = $data[$i][$j];
            }
        }
        
        $high = -1;
        for($j=$data_sz-1;$j>=0;$j--) {
            if($high < $data[$i][$j]) {
                $visible[$i][$j] = "v"; // visible
                $high = $data[$i][$j];
            }
        }
    }
    for($j=0;$j<=$data_sz-1;$j++) {
        $high = -1;
        for($i=0;$i<=$data_sz-1;$i++) {
            if($high < $data[$i][$j]) {
                $visible[$i][$j] = "v"; // visible
                $high = $data[$i][$j];
            }
        }
        
        $high = -1;
        for($i=$data_sz-1;$i>=0;$i--) {
            if($high < $data[$i][$j]) {
                $visible[$i][$j] = "v"; // visible
                $high = $data[$i][$j];
            }
        }
    }
    
    return $visible;
}

function measure_scenic_view($data) {
    $best_scenic_view = 0;
    
    // the 4 possible directions
    $dirs[] = [-1,  0];
    $dirs[] = [ 1,  0];
    $dirs[] = [ 0, -1];
    $dirs[] = [ 0,  1];
    
    $data_sz = sizeof($data);
    for($i=0;$i<=$data_sz-1;$i++) {
        for($j=0;$j<=$data_sz-1;$j++) {
            $this_tree = $data[$i][$j];
            $prod = 1;
            foreach ($dirs as $dir) {
                // length of stretch in this direction
                $l_dir = -1;
                $k = $i;
                $l = $j;
                // problem 1: going outside the map
                // problem 2: the next tree is too high
                // problem 2a: don't check this until we have left this tree
                while(isset($data[$k][$l])
                && ($l_dir == -1 || $data[$k][$l] < $this_tree)) {
                    $l_dir++;
                    $k += $dir[0];
                    $l += $dir[1];
                }
                if(isset($data[$k][$l])) {
                    // we are here because a tree is too high
                    $l_dir++;
                }
                $prod *= $l_dir;
            }
            if($best_scenic_view < $prod) {
                $best_scenic_view = $prod;
            }
        } // j
    } // i
    return $best_scenic_view;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = pivot(get_input($input));
$data_sz = sizeof($data);

$visible = find_visible_trees($data);

$visible_flat = array_merge(...$visible);
$visible_count = array_count_values($visible_flat);
$no = $visible_count["v"];

printf("Result 1: %d\n", $no);

$scenics = measure_scenic_view($data);

printf("Result 2: %d\n", $scenics);

?>
