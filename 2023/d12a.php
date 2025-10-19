<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d12input2.txt', true);

// part 1 or part 2?
$part = 2;

///////////////////////////////////////////////////////////////////////////
// functions

function put_record_back_to_string($record) {
	foreach($record as $key => $content) {
		if($key == 0) {
			$tmp_record = $content . " ";
		} else {
			$tmp_record .= $content . ",";
		}
	}
	return $tmp_record;
}

// possible format:
// .??..??...?##.	1,1,3
// ?###????????		3,2,1

// go through all possible permutations, recursively
// remove only 1 character from the string every time
function permute($record, $in_group) {
	global $permute_result;
	$local_record = put_record_back_to_string($record);
	// memoization
	if(isset($permute_result[$local_record]) && isset($permute_result[$local_record][$in_group])) {
		return $permute_result[$local_record][$in_group];
	}

	// end if there are no numbers left
	if(sizeof($record) == 1) {
		// valid if there are no groups in the rest of the string
		$counts = count_chars($record[0]);
		$hash_value = ord("#");
		if($counts[$hash_value] == 0) {
			$permute_result[$local_record][$in_group] = 1;
			return 1;
		}
		$permute_result[$local_record][$in_group] = 0;
		return 0;
	}

	// end if there is no string left
	if(strlen($record[0]) == 0) {
		// valid if only 1 number was left, and it was 0
		if(sizeof($record) == 2 && $record[1] == 0) {
			$permute_result[$local_record][$in_group] = 1;
			return 1;
		}
		$permute_result[$local_record][$in_group] = 0;
		return 0;
	}

	$local_valid = 0;
	if($in_group == 0) {
		// snip off beginning dot
		if($record[0][0] == ".") {
			$record_local = $record;
			$record_local[0] = substr($record_local[0],1);
			$local_valid += permute($record_local, 0);
		}

		// group might begin
		if($record[0][0] == "#" || $record[0][0] == "?") {
			$record_local = $record;
			$record_local[0] = substr($record_local[0],1);
			$record_local[1]--;
			$local_valid += permute($record_local, 1);
		}
		
		// group might not begin
		if($record[0][0] == "?") {
			$record_local = $record;
			$record_local[0] = substr($record_local[0],1);
			$local_valid += permute($record_local, 0);
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
				$local_valid += permute($record_local, 0);
			}
		} else {
			// group is still going
			if($record[0][0] == "#" || $record[0][0] == "?") {
				$record_local = $record;
				$record_local[0] = substr($record_local[0],1);
				$record_local[1]--;
				$local_valid += permute($record_local, 1);
			}
		}
	}
	$permute_result[$local_record][$in_group] = $local_valid;
	return $local_valid;
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

if($part == 2) {
	foreach($records as $record) {
		$record_tmp = array();
		$springs = $record[0];
		$numbers = $record;
		unset($numbers[0]);
		for($i=2;$i<=5;$i++) {
			$springs .= "?" . $record[0];
		}
		$record_tmp[] = $springs;
		for($i=1;$i<=5;$i++) {
			$record_tmp = array_merge($record_tmp, $numbers);
		}
		$records2[] = $record_tmp;
	}
	$records = $records2;
}

$permute_result = array();
$valid_permutations = 0;
foreach($records as $record) {
	$valid_permutations += permute($record, 0);
}

printf("Number of valid permutations: %d\n", $valid_permutations);

?>
