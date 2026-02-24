<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d10input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = explode(" ", $line);
		}
	}
	return $data;
}

function cycle_check($cycle_no, $x) {
    if($cycle_no % 40 == 20) {
        return $cycle_no * $x;
    } else {
        return 0;
    }
}

function update_crt($cycle_no, $x) {
    $crt_pos = ($cycle_no -1) % 40;
    $sprite_pos_left = $x - 1;
    $sprite_pos_right = $x + 1;
    if($sprite_pos_left <= $crt_pos && $crt_pos <= $sprite_pos_right) {
        print("#");
    } else {
        print(".");
    }
    if($crt_pos == 39) {
        print("\n");
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$cycle_no = 0; // how many cycles have occurred
$x = 1;
$sssum = 0; // signal strength sum

foreach ($data as $instruction) {
    if(strcmp($instruction[0], "noop") == 0) {
        $cycle_no++;
        update_crt($cycle_no, $x);
        $sssum += cycle_check($cycle_no, $x);
    } else {
        $cycle_no++;
        update_crt($cycle_no, $x);
        $sssum += cycle_check($cycle_no, $x);
        
        $cycle_no++;
        update_crt($cycle_no, $x);
        $sssum += cycle_check($cycle_no, $x);
        
        $x += $instruction[1];
    }
}

printf("Result 1: %d\n", $sssum);

?>
