<?php

include "dijkstra.php";

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d12input2.txt', true);

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

function print_previous($previous, $data_cols, $data_rows) {
    ksort($previous);
    foreach ($previous as $i => $j) {
        if($i % $data_cols == 0) {
            print("\n");
        }
        printf("%4d\t", $previous[$i]);
    }
}

function convert_to_id($x, $y) {
    global $data_rows, $data_cols;
    return $x + $data_cols * $y;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = pivot(get_input($input));

$data_cols = sizeof($data);
$data_rows = sizeof($data[0]);
$data_size = $data_cols * $data_rows;

$graph_row = array_fill(0, $data_size, 0);
$graph = array_fill(0, $data_size, $graph_row);

$all_the_as = [];

foreach ($data as $i => $column) {
    foreach ($column as $j => $cell) {
        $cell_id = convert_to_id($i, $j);
        $me = $data[$i][$j];
        if($me == "S") {
            $me = "a";
            $S = [$i, $j];
        }
        if($me == "E") {
            $me = "z";
            $E = [$i, $j];
        }
        if($me == "a") {
            $all_the_as[] = [$i, $j];
        }
        if($i < $data_cols - 1) {
            $cell_right = convert_to_id($i + 1, $j);
            $right = $data[$i+1][$j];
            if($right == "S") {
                $right = "a";
            }
            if($right == "E") {
                $right = "z";
            }
            if(ord($right) <= ord($me) + 1) {
                $graph[$cell_id][$cell_right] = 1;
            }
            if(ord($me) <= ord($right) + 1) {
                $graph[$cell_right][$cell_id] = 1;
            }
        }
        if($j < $data_rows - 1) {
            $cell_below = convert_to_id($i, $j + 1);
            $below = $data[$i][$j+1];
            if($below == "S") {
                $below = "a";
            }
            if($below == "E") {
                $below = "z";
            }
            if(ord($below) <= ord($me) + 1) {
                $graph[$cell_id][$cell_below] = 1;
            }
            if(ord($me) <= ord($below) + 1) {
                $graph[$cell_below][$cell_id] = 1;
            }
        }
    }
}

$start_id = convert_to_id($S[0], $S[1]);
$distance = Dijkstra($graph, $start_id, $data_size);

$end_id = convert_to_id($E[0], $E[1]);
printf("Result 1: %d\n", $distance[$end_id]);

///////////////////////////////////////////////////////////////////////////

$min_distance = 10000; // big number
printf("%d a's in all\n", sizeof($all_the_as));
foreach ($all_the_as as $i => $an_a) {
    if($i % 10 == 0) {
        printf("%d ", $i);
    }
    $start_id = convert_to_id($an_a[0], $an_a[1]);
    $distance = Dijkstra($graph, $start_id, $data_size);
    $a_distance = $distance[$end_id];
    if($a_distance < $min_distance) {
        $min_distance = $a_distance;
    }
}

printf("Result 2: %d\n", $min_distance);

?>
