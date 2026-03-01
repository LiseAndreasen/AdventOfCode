<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d13input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function make_a_tree($line, &$ptr) {
    $keep_going = true; // dummy
    $num = "";
    $num_going = false;
    $tree = [];
    while($keep_going == true) {
        if(strlen($line) <= $ptr) {
            // end of the line
            return $tree;
        }
        switch ($line[$ptr]) {
            case "[":
                $ptr++;
                $tree[] = make_a_tree($line, $ptr);
                // the next character should be a ], skip it
                $ptr++;
                break;
            case "]":
                // first save any number
                if($num_going) {
                    $tree[] = $num;
                }
                // shouldn't be handled on this level, return
                return $tree;
            case ",":
                // if previous was a number, save it
                if($num_going) {
                    $tree[] = $num;
                    $num = "";
                    $ptr++;
                    $num_going = false;
                } else {
                    // previous was a bracket, skip comma
                    $ptr++;
                }
                break;
            default:
                // if we are here, it is a number
                $num_going = true;
                $num .= $line[$ptr];
                $ptr++;
                break;
        }
    }
}

function get_input($input) {
	// absorb input file, line by line
	$i = 1;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>1) {
		    $ptr = 0;
		    $data[$i][] = make_a_tree($line, $ptr);
		} else {
		    $i++;
		}
	}
	return $data;
}

// might return -1, 0 and 1
// a < b: -1
// a = b: 0
// a > b: 1
function compare($list1, $list2) {
    $list1_is_array = is_array($list1);
    $list2_is_array = is_array($list2);
    
    // If both values are lists
    if($list1_is_array && $list2_is_array) {
        $list_len = min(sizeof($list1), sizeof($list2));
        
        for($i=0;$i<$list_len;$i++) {
            $comparison = compare($list1[$i], $list2[$i]);
            if($comparison != 0) {
                return $comparison;
            }
        }
        
        // if i reach this point, the lists might not have the same length
        if(sizeof($list1) < sizeof($list2) ) {
            return -1;
        }
        if(sizeof($list2) < sizeof($list1) ) {
            return 1;
        }
        
        // if i reach this point, it was inconclusive
        return 0;
    }
    
    // If both values are integers
    if(!$list1_is_array && !$list2_is_array) {
        if($list1 < $list2) {
            return -1;
        }
        if($list2 < $list1) {
            return 1;
        }
        // if i reach this point, it was inconclusive
        return 0;
    }
    
    // if i reach this point, it's 1 integer and 1 list
    if($list1_is_array) {
        $list2 = [$list2];
        return compare($list1, $list2);
    } else {
        $list1 = [$list1];
        return compare($list1, $list2);
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$in_right_order = 0;
foreach ($data as $i => $pair) {
    // we want -1, for 1st item < 2nd item
    $comparison = compare($pair[0], $pair[1]);
    if($comparison == -1) {
        $in_right_order += $i;
    }
}

printf("Result 1: %d\n", $in_right_order);

///////////////////////////////////////////////////////////////////////////

// collapse data
$data = array_merge(...$data);

// add 2 divider packets

$line_two = "[[2]]";
$ptr = 0;
$tree_two = make_a_tree($line_two, $ptr);
$data[] = $tree_two;

$line_six = "[[6]]";
$ptr = 0;
$tree_six = make_a_tree($line_six, $ptr);
$data[] = $tree_six;

usort($data, "compare");

foreach ($data as $i => $tree) {
    if(compare($tree, $tree_two) == 0) {
        $pos_two = $i;
    }
    if(compare($tree, $tree_six) == 0) {
        $pos_six = $i;
    }
}

printf("Result 2: %d\n", ($pos_two + 1) * ($pos_six + 1));

?>
