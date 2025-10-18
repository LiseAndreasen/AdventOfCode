<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d12input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

// possible format:
// .??..??...?##.	1,1,3
// ?###????????		3,2,1

// go through all possible permutations, recursively
// remove only 1 character from the string every time
function permute($record, $in_group) {

	global $valid_permutations;
	
	// end if there are no numbers left
	if(sizeof($record) == 1) {
		// valid if there are no groups in the rest of the string
		$counts = count_chars($record[0]);
		$hash_value = ord("#");
		if($counts[$hash_value] == 0) {
			$valid_permutations++;
		}
		return;
	}

	// end if there is no string left
	if(strlen($record[0]) == 0) {
		// valid if only 1 number was left, and it was 0
		if(sizeof($record) == 2 && $record[1] == 0) {
			$valid_permutations++;
		}
		return;
	}

	if($in_group == 0) {
		// snip off beginning dot
		if($record[0][0] == ".") {
			$record_local = $record;
			$record_local[0] = substr($record_local[0],1);
			permute($record_local, 0);
		}

		// group might begin
		if($record[0][0] == "#" || $record[0][0] == "?") {
			$record_local = $record;
			$record_local[0] = substr($record_local[0],1);
			$record_local[1]--;
			permute($record_local, 1);
		}
		
		// group might not begin
		if($record[0][0] == "?") {
			$record_local = $record;
			$record_local[0] = substr($record_local[0],1);
			permute($record_local, 0);
		}
	}
	
	if($in_group == 1) {
		// group is just over
		if($record[1] == 0) {
			if($record[0][0] == "." || $record[0][0] == "?") {
				$record_local = $record;
				$record_local[0] = substr($record_local[0],1);
				unset($record_local[1]);
				// reindex
				$record_local = [...$record_local];
				permute($record_local, 0);
			} else {
				// this permutation was impossible
				return;
			}
		} else {
			// group is still going
			if($record[0][0] == "#" || $record[0][0] == "?") {
				$record_local = $record;
				$record_local[0] = substr($record_local[0],1);
				$record_local[1]--;
				permute($record_local, 1);
			} else {
				// this permutation was impossible
				return;
			}
		}
	}
	
}

///////////////////////////////////////////////////////////////////////////
// main program

// absorb input file, line by line
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$tmp1 = explode(" ", $line);
		$tmp2 = explode(",", $tmp1[1]);
		$records[] = array_merge(array($tmp1[0]), $tmp2);
	}
}

//print_r($records);

$valid_permutations = 0;
foreach($records as $record) {
	permute($record, 0);
}

printf("Number of valid permutations: %d\n", $valid_permutations);

?>
