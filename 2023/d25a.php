<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d25input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			// cmg: qnr nvd lhk bvb
			$data1 = explode(": ", $line);
			$data2 = explode(" ", $data1[1]);
			ksort($data2);
			$data[$data1[0]] = $data2;
		}
	}
	ksort($data);
	return $data;
}

function make_subgraphs($conn, $subg, $subg_name) {
	foreach($conn as $name1 => $list) {
		foreach($list as $name2 => $dummy) {
			if(strcmp($subg_name[$name1], $subg_name[$name2]) != 0) {
				// these 2 are in different subgraphs, fix that
				$subg[$subg_name[$name1]] = array_merge($subg[$subg_name[$name1]], $subg[$subg_name[$name2]]);
				$old_subg_name = $subg_name[$name2];
				foreach($subg[$subg_name[$name2]] as $name3) {
					$subg_name[$name3] = $subg_name[$name1];
				}
				unset($subg[$old_subg_name]);
			}
			if(sizeof($subg) == 1) {
				return -1;
			}
		}
		if(sizeof($subg) == 1) {
			return -1;
		}
	}
	$prod = 1;
	foreach($subg as $g) {
		$prod *= sizeof($g);
	}
	return $prod;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

// first part of this puzzle requires this commented bit to be run
/*
// php d25a.php > d25graphviz.dot
print("strict graph ip_map {\n");
foreach($data as $name => $list) {
	printf("%s -- {%s}\n", $name, implode(" ", $list));
}
print("}\n");
exit();
*/

// data obtained using dot -Tpng d25graphviz.dot -o d25graphviz.png -Kneato

// delete connections between xhl:shj, fxk:bcf and cgt: zgp
$i = array_search("shj", $data["xhl"]);
unset($data["xhl"][$i]);
$i = array_search("bcf", $data["fxk"]);
unset($data["fxk"][$i]);
$i = array_search("zgp", $data["cgt"]);
unset($data["cgt"][$i]);

// make list of connections
// make subgraphs, at first with only 1 element
foreach($data as $name1 => $list) {
	foreach($list as $name2) {
		$conn[$name1][$name2] = 1;
		$conn[$name2][$name1] = 1;
		$subg[$name2][$name2] = $name2;
		$subg_name[$name2] = $name2;
	}
	$subg[$name1][$name1] = $name1;
	$subg_name[$name1] = $name1;
}

foreach($conn as $list) {
	ksort($list);
}
ksort($conn);

$num = make_subgraphs($conn, $subg, $subg_name);

printf("Result 1: %d\n", $num);

?>
