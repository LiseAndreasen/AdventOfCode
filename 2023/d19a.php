<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d19input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	$part_rules = 1;
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			if($part_rules == 1) {
				// px{a<2006:qkq,m>2090:A,rfg}
				$rule1 = explode("{", $line);
				$name = $rule1[0];
				$rule2 = str_replace("}", "", $rule1[1]);
				$rule3 = explode(",", $rule2);
				$rule7 = [];
				foreach($rule3 as $subrule) {
					$rule4 = explode(":", $subrule);
					if(sizeof($rule4) == 1) {
						$rule7["last"] = $subrule; // simple rule
					} else {
						// condition rule
						$rule5 = $rule4[1];
						$rule6 = explode("<", $rule4[0]);
						if(sizeof($rule6) == 2) {
							$rule8 = $rule4[0];
							$rule7[$rule8] = ["<", $rule6, $rule5];
						} else {
							$rule6 = explode(">", $rule4[0]);
							$rule8 = $rule4[0];
							$rule7[$rule8] = [">", $rule6, $rule5];
						}
					}
				}
				$rules[$name] = $rule7;
			} else {
				// {x=787,m=2655,a=1222,s=2876}
				$part1 = str_replace("{", "", $line);
				$part1 = str_replace("}", "", $part1);
				$part2 = explode(",", $part1);
				foreach($part2 as $subpart) {
					$part3 = explode("=", $subpart);
					$part4[$part3[0]] = $part3[1];
				}
				$parts[] = $part4;
			}
		} else {
			$part_rules++;
		}
	}
	return [$rules, $parts];
}

function compare_this($value1, $comparator, $value2) {
	if($comparator == "<") {
		return $value1 < $value2;
	}
	if($comparator == ">") {
		return $value1 > $value2;
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

[$rules, $parts] = get_input($input);
//print_r($rules);
//print_r($parts);

$accepted_sum = 0;

foreach($parts as $part) {
	$rule = "in";
	$decided = "0";

//print("\n\n");
//printf("%s\n", implode(",", $part));
	
	while($decided == "0") {
//print("$rule,");
		foreach($rules[$rule] as $key => $subrule) {
//print_r($subrule);
//print("\n");
			if(strcmp($key, "last") == 0) {
				if($subrule == "A") {
//					print("Part accepted!\n");
					$decided = "A";
					break;
				}
				if($subrule == "R") {
//					print("\nPart rejected!\n");
					$decided = "R";
					break;
				}
				// if we are here, the rule is to jump
				$rule = $subrule;
				break;
			} else {
				$rule_type = $subrule[0];
				$rule_cond = $subrule[1];
				$rule_jump = $subrule[2];
				switch($rule_cond[0]) {
					case "x":
						$this_value = $part["x"];
						break;
					case "m":
						$this_value = $part["m"];
						break;
					case "a":
						$this_value = $part["a"];
						break;
					case "s":
						$this_value = $part["s"];
						break;
				}
				$compare = compare_this($this_value, $rule_type, $rule_cond[1]);
				if($compare) {
					if($rule_jump == "A") {
//						print("Part accepted!\n");
						$decided = "A";
						break;
					}
					if($rule_jump == "R") {
//						print("\nPart rejected!\n");
						$decided = "R";
						break;
					}
					// if we are here, the rule is to jump
					$rule = $rule_jump;
					break;
				} else {
					continue;
				}
			}
		}
	}
	
	if($decided == "A") {
		$this_sum = array_sum($part);
		$accepted_sum += $this_sum;
//		print_r($part);
	}
}

printf("Result 1: %d\n", $accepted_sum);

?>
