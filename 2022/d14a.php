<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d14input2.txt', true);
$sand_entry = [500, 0];
$part = 2;

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	
    global $sand_entry, $part;
	$xmin = $sand_entry[0];
	$xmax = $sand_entry[0];
	$ymin = $sand_entry[1];
	$ymax = $sand_entry[1];
	$data[0] = [[$xmin, $ymin], [$xmax, $ymax]];

	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
		    $data1 = explode(" -> ", $line);
		    $data2 = [];
		    foreach ($data1 as $p) {
		        $xy = explode(",", $p);
		        $all_the_xs[$xy[0]] = $xy[0];
		        $all_the_ys[$xy[1]] = $xy[1];
		        $data2[] = $xy;
		    }
			$data[] = $data2;
		}
	}
	
	// add floor?
	if($part == 2) {
	    $floor = max($all_the_ys) + 2;
	    $data[] = [[$sand_entry[0] - $floor, $floor],
	        [$sand_entry[0] + $floor, $floor]];
	    $all_the_xs[$sand_entry[0] - $floor] = $sand_entry[0] - $floor;
	    $all_the_xs[$sand_entry[0] + $floor] = $sand_entry[0] + $floor;
	    $all_the_ys[$floor] = $floor;
	}

	$data[0] = [[min($all_the_xs) - 1, $sand_entry[1]],
	    [max($all_the_xs) + 1, max($all_the_ys) + 1]];

	return $data;
}

function print_map($map) {
    global $sand_entry;
    
    foreach($map[$sand_entry[0]] as $j => $cell) {
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

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

// construct cave and cave map
$cave = [];
foreach ($data as $k => $path) {
    if($k == 0) {
        // these are actually max and min points
        [[$xmin, $ymin], [$xmax, $ymax]] = $path;
        $cave_map_col = array_fill($ymin, $ymax - $ymin + 1, ".");
        $cave_map = array_fill($xmin, $xmax - $xmin + 1, $cave_map_col);
        continue;
    }
    
    $first_point = true;
    foreach ($path as $i => $point) {
        [$px, $py] = $point;
        
        if(!$first_point) {
            [$rx, $ry] = $prev;
            if($px == $rx) {
                // vertical path
                $begin = min($py, $ry);
                $end = max($py, $ry);
                for($j=$begin+1;$j<$end;$j++) {
                    $cave[$px][$j] = "#";
                    $cave_map[$px][$j] = "#";
                }
            } else {
                // horizontal path
                $begin = min($px, $rx);
                $end = max($px, $rx);
                for($j=$begin+1;$j<$end;$j++) {
                    $cave[$j][$py] = "#";
                    $cave_map[$j][$py] = "#";
                }
            }
        } else {
            $first_point = false;
        }
        $prev = $point;
        
        $cave[$px][$py] = "#";
        $cave_map[$px][$py] = "#";
    }
}

$sand = 0;
$can_flow = true; // more sand can still flow

while ($can_flow) {
    $s = $sand_entry; // sand starts here
    $is_flowing = true; // this grain of sand is still flowing
    
    while($is_flowing) {
        $is_flowing = false;
        
        if($s[0] == $xmin || $s[0] == $xmax) {
            // this grain will just keep going
            break 2;
        }
        
        // we want a y, min(y): $s[1] < y
        $new_ys = array_keys(array_filter($cave[$s[0]], function($k) {
            global $s; return $s[1] < $k;
        }, ARRAY_FILTER_USE_KEY));
        if(sizeof($new_ys) == 0) {
            // this grain will just keep going
            break 2;
        }
        $new_y = min($new_ys);
        
        if($s[1] == $new_y - 1) {
            // grain might go sideways
            if(!isset($cave[$s[0] - 1][$s[1] + 1])) {
                if($s[0] < $xmin) {
                    // this grain will just keep going
                    break 2;
                }
                $s = [$s[0] - 1, $s[1] + 1];
                $is_flowing = true;
                continue;
            }
            if(!isset($cave[$s[0] + 1][$s[1] + 1])) {
                if($xmax < $s[0]) {
                    // this grain will just keep going
                    break 2;
                }
                $s = [$s[0] + 1, $s[1] + 1];
                $is_flowing = true;
                continue;
            }
            
            // grain has nowhere to go and comes to rest
            $cave[$s[0]][$s[1]] = "o";
            $cave_map[$s[0]][$s[1]] = "o";
            $sand++;
            if($part == 2) {
                // check whether there's now sand at the entry point
                if($s[0] == $sand_entry[0] && $s[1] == $sand_entry[1]) {
                    break 2;
                }
            }
        } else {
            // grain drops
            $s[1] = $new_y - 1;
            $is_flowing = true;
        }
    }
}

printf("Result %d: %d\n", $part, $sand);

?>
