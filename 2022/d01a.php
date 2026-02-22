<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d01input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>1) {
			$data[] = $line;
		} else {
		    $data[] = "*";
		}
	}
	$data[] = "*"; // to mark the end
	return $data;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

//print_r($data);

$food_sum = 0;
$food_sum_max = 0;
foreach($data as $food) {
    if($food != "*") {
        $food_sum += $food;
    } else {
        $all_food_sums[] = $food_sum; // added in part 2
        if($food_sum_max < $food_sum) {
            $food_sum_max = $food_sum;
        }
        $food_sum = 0;
    }
}

printf("Result 1: %d\n", $food_sum_max);

sort($all_food_sums);
//print_r($all_food_sums);

$max_sum = 0;
for($i=0;$i<3;$i++) {
    $max_sum += array_pop($all_food_sums);
}

printf("Result 2: %d\n", $max_sum);

?>
