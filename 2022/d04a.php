<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d04input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			preg_match('#(.*)-(.*),(.*)-(.*)#', $line, $m);
			[$all, $d1, $d2, $d3, $d4] = $m;
			$data[] = [$d1, $d2, $d3, $d4];
		}
	}
	return $data;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$containments = 0;
$overlaps = 0; // everything related to overlaps added in part 2
foreach ($data as $sections) {
    [$s11, $s12, $s21, $s22] = $sections;
    if($s11 < $s21) {
        // the sections for elf 1 may contain all of the sections for elf 2
        if($s22 <= $s12) {
            $containments++;
        }
        if($s21 <= $s12) {
            $overlaps++;
        }
    }
    if($s21 < $s11) {
        // the sections for elf 2 may contain all of the sections for elf 1
        if($s12 <= $s22) {
            $containments++;
        }
        if($s11 <= $s22) {
            $overlaps++;
        }
    }
    if($s11 == $s21) {
        // one of these will contain the other
        $containments++;
        $overlaps++;
    }
}

printf("Result 1: %d\n", $containments);
printf("Result 2: %d\n", $overlaps);

?>
