<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d03input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = $line;
		}
	}
	return $data;
}

// Lowercase item types a through z have priorities 1 through 26.
// Uppercase item types A through Z have priorities 27 through 52.
// ascii: A = 65, Z = 90, a = 97, z = 121
function priority($char) {
    $ascii = ord($char);
    if($ascii < 95) {
        // upper case
        return $ascii - 64 + 26;
    } else {
        return $ascii - 96;
    }
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$priority_sum = 0;
foreach($data as $rucksack) {
    $n = strlen($rucksack);
    $r1 = str_split(substr($rucksack, 0, $n/2));
    $r2 = str_split(substr($rucksack, $n/2));
    $intersect = array_intersect($r1, $r2);
    $priority_sum += priority(array_shift($intersect));
}

printf("Result 1: %d\n", $priority_sum);

$priority_sum = 0;
for($i=0;$i<sizeof($data)/3;$i++) {
    $d1 = str_split($data[$i * 3]);
    $d2 = str_split($data[$i * 3 + 1]);
    $d3 = str_split($data[$i * 3 + 2]);
    $intersect = array_intersect(array_intersect($d1, $d2), $d3);
    $priority_sum += priority(array_shift($intersect));
}

printf("Result 2: %d\n", $priority_sum);

?>
