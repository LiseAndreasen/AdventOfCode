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

function test_part($part, $rules) {
	$rule = "in";
	$decided = "0";
	
	while($decided == "0") {
		foreach($rules[$rule] as $key => $subrule) {
			if(strcmp($key, "last") == 0) {
				if($subrule == "A") {
					$decided = "A";
					break;
				}
				if($subrule == "R") {
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
						$decided = "A";
						break;
					}
					if($rule_jump == "R") {
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
	return $decided;
}

///////////////////////////////////////////////////////////////////////////
// main program

[$rules, $parts] = get_input($input);

$accepted_sum = 0;

foreach($parts as $part) {
	$decided = test_part($part, $rules);
	if($decided == "A") {
		$this_sum = array_sum($part);
		$accepted_sum += $this_sum;
	}
}

printf("Result 1: %d\n", $accepted_sum);

$accepted_sum = 0;
$part_down["x"] = 1;
$part_down["m"] = 1;
$part_down["a"] = 1;
$part_down["s"] = 1;
$part_up["x"] = 4000;
$part_up["m"] = 4000;
$part_up["a"] = 4000;
$part_up["s"] = 4000;
$choices = [["in", $part_down, $part_up]];
//print_r($choices);

while(0 < sizeof($choices)) {
	$choice = array_shift($choices);
	[$rule, $part_down, $part_up] = $choice;
	foreach($rules[$rule] as $key => $subrule) {
		if(strcmp($key, "last") != 0) {
			[$comp, $char, $jump] = $subrule;
			[$char_char, $char_val] = $char;
			if($part_down[$char_char] <= $char_val && $char_val <= $part_up[$char_char]) {
				// split out the cases and possibly add to queue

				$part_down_low = $part_down;
				$part_up_low = $part_up;
				$part_down_high = $part_down;
				$part_up_high = $part_up;
				if($comp == "<") {
					$part_up_low[$char_char] = $char_val - 1;
					$part_down_high[$char_char] = $char_val;
					switch($jump) {
						case "A":
							$accepted_sum += ($part_up_low["x"] - $part_down_low["x"] + 1)
								* ($part_up_low["m"] - $part_down_low["m"] + 1)
								* ($part_up_low["a"] - $part_down_low["a"] + 1)
								* ($part_up_low["s"] - $part_down_low["s"] + 1);
							break;
						case "R":
							break;
						default:
							$new_choice = [$jump, $part_down_low, $part_up_low];
							$choices[] = $new_choice;
							break;
					}
					$part_down = $part_down_high;
					$part_up = $part_up_high;
				} else {
					$part_up_low[$char_char] = $char_val;
					$part_down_high[$char_char] = $char_val + 1;
					switch($jump) {
						case "A":
							$accepted_sum += ($part_up_high["x"] - $part_down_high["x"] + 1)
								* ($part_up_high["m"] - $part_down_high["m"] + 1)
								* ($part_up_high["a"] - $part_down_high["a"] + 1)
								* ($part_up_high["s"] - $part_down_high["s"] + 1);
							break;
						case "R":
							break;
						default:
							$new_choice = [$jump, $part_down_high, $part_up_high];
							$choices[] = $new_choice;
							break;
					}
					$part_down = $part_down_low;
					$part_up = $part_up_low;
				}
			} else {
				// there's no splitting
			}
		} else {
			switch($subrule) {
				case "A":
					$accepted_sum += ($part_up["x"] - $part_down["x"] + 1)
						* ($part_up["m"] - $part_down["m"] + 1)
						* ($part_up["a"] - $part_down["a"] + 1)
						* ($part_up["s"] - $part_down["s"] + 1);
					break;
				case "R":
					break;
				default:
					$new_choice = [$subrule, $part_down, $part_up];
					$choices[] = $new_choice;
					break;
			}
		}
	}
}

printf("Result 2: %s\n", $accepted_sum);

?>
