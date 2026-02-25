<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d11input2.txt', true);
$part = 2;
if($part == 1) {
    $rounds = 20;
} else {
    $rounds = 10000; // 10000
}

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$tmp_data = explode(" ", $line);
			while($tmp_data[0] == "") {
			    array_shift($tmp_data);
			}
			switch ($tmp_data[0]) {
			    case "Monkey":
			        $monkey_no = $tmp_data[1];
			        $monkey_no = substr($monkey_no, 0, -1);
			        break;
			    case "Starting":
			        // remove first 2 items
			        array_shift($tmp_data);
			        array_shift($tmp_data);
			        foreach ($tmp_data as $key => $item) {
			            $tmp_data[$key] = str_replace(",", "", $item);
			        }
			        $data[$monkey_no]["starting"] = $tmp_data;
			        break;
			    case "Operation:":
			        // remove first item
			        array_shift($tmp_data);
			        $data[$monkey_no]["operation"] = $tmp_data;
			        break;
			    case "Test:":
			        array_shift($tmp_data);
			        $data[$monkey_no]["test"] = $tmp_data;
			        break;
			    case "If":
			        array_shift($tmp_data);
			        $true_false = array_shift($tmp_data);
			        $true_false = substr($true_false, 0, -1);
			        $data[$monkey_no]["if"][$true_false] = $tmp_data;
			        break;
			}
		}
	}
	return $data;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
$lcm = 1;

// construct queues and inspection numbers and lcm for the monkeys
foreach ($data as $monkey_no => $monkey) {
    $queues[$monkey_no] = $monkey["starting"];
    $inspections[$monkey_no] = 0;
    $lcm *= $data[$monkey_no]["test"][2];
}

for($i=0;$i<$rounds;$i++) {
    foreach ($data as $monkey_no => $monkey) {
        foreach ($queues[$monkey_no] as $item_no => $item) {
            $inspections[$monkey_no]++;
            if($monkey["operation"][3] == "+") {
                if(strcmp($monkey["operation"][4], "old") == 0) {
                    $item += $item;
                } else {
                    $item += $monkey["operation"][4];
                }
            } else { // *
                if(strcmp($monkey["operation"][4], "old") == 0) {
                    $item *= $item;
                } else {
                    $item *= $monkey["operation"][4];
                }
            }
            if($part == 1) {
                $item = floor($item/3);
            }
            // note: numbers may get really big
            // divide by product of "divisible by" numbers = lcm
            $item = $item % $lcm;
            if($item % $monkey["test"][2] == 0) {
                $queues[$monkey["if"]["true"][3]][] = $item;
            } else {
                $queues[$monkey["if"]["false"][3]][] = $item;
            }
            unset($queues[$monkey_no][$item_no]);
        }
    }
}

rsort($inspections);
printf("Result %s: %d\n", $part, $inspections[0] * $inspections[1]);

?>
