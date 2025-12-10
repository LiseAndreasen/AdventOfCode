<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d10input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line) < 2) {
			continue;
		}
		$buttons = array();
		$datatmp1 = explode(" ", $line);
		$datatmp2 = str_replace(array("[", "]"), "", $datatmp1[0]);
		$lights[] = str_split($datatmp2);
		foreach($datatmp1 as $paren) {
			if(preg_match('/\((.*)\)/', $paren, $matches)) {
				$buttons[] = explode(",", $matches[1]);
			}
			if(preg_match('/{(.*)}/', $paren, $matches)) {
				$joltage[] = explode(",", $matches[1]);
			}
		}
		$buttons_arr[] = $buttons;
	}
	return array($lights, $buttons_arr, $joltage);
}

function togg($char) {
	if($char == ".") {
		return "#";
	} else {
		return ".";
	}
}

function press_buttons1($lights_now, $target_lights, $buttons, $presses) {
	global $presses1_best;
	if(sizeof(array_diff_assoc($lights_now, $target_lights)) == 0) {
		// target reached
		if($presses < $presses1_best) {
			$presses1_best = $presses;
			return 1;
		}
	}
	
	if(sizeof($buttons) == 0) {
		// there are no buttons left to try
		return 2;
	}
	
	$this_button = $buttons[0];
	$rest_of_buttons = $buttons;
	unset($rest_of_buttons[0]);
	sort($rest_of_buttons);

	// try without using this button
	press_buttons1($lights_now, $target_lights, $rest_of_buttons, $presses);
	
	// now try with this button
	foreach($this_button as $pos) {
		$lights_now[$pos] = togg($lights_now[$pos]);
	}
	press_buttons1($lights_now, $target_lights, $rest_of_buttons, $presses + 1);
}

// i stopped using this because it didn't really work
// heat death of the universe or something
function press_buttons2a($jolt_now, $target_jolt, $buttons, $presses, $all_presses) {
	global $all_buttons;
	global $progress;
	$offset = $all_buttons - sizeof($buttons); // this is the number of the first button
	global $presses2_best;
	if(sizeof(array_diff_assoc($jolt_now, $target_jolt)) == 0) {
		// target reached
		if($presses < $presses2_best) {
			$presses2_best = $presses;
			return 1;
		}
	}

	if(sizeof($buttons) == 0) {
		// there are no buttons left to try
		return 2;
	}
	$progress++;
	if($progress % 100000 == 0) {
		print(".");
	}

	$this_button = $buttons[0];
	$rest_of_buttons = $buttons;
	unset($rest_of_buttons[0]);
	sort($rest_of_buttons);

	// try pressing this button 0 times
	press_buttons2a($jolt_now, $target_jolt, $rest_of_buttons, $presses, $all_presses);
	
	// try pressing this button until it is too much
	while(0 == 0) {
		foreach($this_button as $pos) {
			$jolt_now[$pos]++;
			if($target_jolt[$pos] < $jolt_now[$pos]) {
				// that was too much!
				return 3;
			}
		}
		// it was okay to press this button
		$all_presses[$offset]++;
		$presses++;
		press_buttons2a($jolt_now, $target_jolt, $rest_of_buttons, $presses, $all_presses);
	}
}

function print_format_for_python($c, $a, $jolts) {
	$text = "A = [";
	foreach($a as $a_sub) {
		$text .= "[";
		$text .= implode(",", $a_sub);
		$text .= "],";
	}
	$text .= "]\n";
	
	$text .= "c = [";
	$text .= implode(",", $c);
	$text .= "]\n";
	
	$text .= "jolts = [";
	$text .= implode(",", $jolts);
	$text .= "]\n";
	
	$text .= "res = scipy.optimize.linprog(c, A_eq=A, b_eq=jolts, integrality=1)\n";
	$text .= "ans += sum(res.x)\n";
	
	print_r($text);

	print("\n");
}

// using python
// 1 line stolen from
// https://topaz.github.io/paste/#XQAAAQD2BwAAAAAAAAA0m0pnuFI8c/sCZpj5NzYXHXBnhdocW1pSPf80bErWxIRLp+gSTO1p2Tm0sOGw2RPAi03lJjx12LPjomkYSl9Gz+lVkv8yQ36ctp6NxlqZXkC2MKpgY88Gk70oQ0uBAIOKs8Vet4cngEBBZp0uoGVk9a7rPwWfJ2YS/zwxL/Tpm7sdIb+Ry6YZU/eiTQ4qHAVvy9JYHukHZ1hyEg7gakxOTgjK+oGqZj+I4eWdIYfDzqGvjqDXNZxJtRle+yhspxE491qQVAsvg0J4A2eS638GBJA7wAJgrV5SCeYu42Mztd5QAKy3A+7MsRy34cKuB26/rgAltjnGhqAUPoMpALSgbRDDYRTpt5ia6LlBVTlwByFJMP+EfLIXFmGNqBkhlN6MW+Hl3w/Tm16VbV21utQdkNWSRpKRthenQfwmSm9zWKkkjvkyXjkZoj9bGYiNQUZMBLFdyIF3HTudnzCjFc/y/ZPCKdzyCbby+khe0yX2N90UD9xkeVN5/th0QlHdXe9txhSTeEqwaw05bNkV1v2KL+slKRYzwcmVpJ6+RRUqc01VK5SwBT2FT+IqiNUb35fN/qOHddNamWi6jumJUe5tubePloqePUL9ZvCFGELM+hlax4h9pB/FrNXyn5Vxt1j5V6Y8LxAmeD8F53DstgJ7JGpxdH0uboTv1qUNFbLMFlMktkXttgZZOaZgr3q5hKKH+upCZHg8P3Br+gvOTcu0WpfLcNrPmx92bWCR665I+9CFtj/xZ8U9QCrI9plLOrsniJu+5l+cEuhiJW4gDhT2RO7aKMFQL/lW86tTGGIJuKWV29CCrzaci+Xs4Uj9ArHgFLtpuC3Dk0rB4uYcGUsfILIloKTRjBchlUiyUUgRA7FJYfQgRHuMHvZUpszlkkE1Y0yXRVIgbTB5bS5IEtJXjT+F31ilQ5p76p1BcN3GLFQjIG+V2aEiNHrht6EzKNdJYTiZJQV+8M/I+LmygrwFEYksLlNIjFsjH3TwAAHVSDZj0qTLRbhFUhZwqtvUp64NSwxtDvKqi4FkXp9p/qooFEj20XZ87taXR1PL1pjTYRVsbFlqoz93FgNfPgNsDA97YTbWHO2mIMoJv9EKpx3DtnHOkags/+VuGM4=

function press_buttons2b($target_jolt, $buttons) {
	$buttons_sz = sizeof($buttons);
	$jolt_sz = sizeof($target_jolt);
	$c = array_fill(0, $buttons_sz, 1);
	$a_sub = array_fill(0, $buttons_sz, 0);
	$a = array_fill(0, $jolt_sz, $a_sub);
	foreach($buttons as $key => $button) {
		foreach($button as $counter) {
			$a[$counter][$key] = 1;
		}
	}
	$jolts = $target_jolt;
	print_format_for_python($c, $a, $jolts);
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);
[$lights, $buttons_arr, $joltage] = $data;

$presses1_sum = 0;
foreach($lights as $key => $light) {
	$buttons = $buttons_arr[$key];
	$light_sz = sizeof($light);
	$begin = array_fill(0, $light_sz, ".");
	$presses1_best = 1000000; // function will update this value
	press_buttons1($begin, $light, $buttons, 0);
	$presses1_sum += $presses1_best;
}

printf("#Result 1: %d\n", $presses1_sum);

// prepare a python script
print("import scipy\n\nans = 0\n\n");
foreach($joltage as $key => $jolt) {
	$buttons = $buttons_arr[$key];
	press_buttons2b($jolt, $buttons);
}
print("print(ans)\n");

// this version of the script is prepared for part 2, actual data
// php d10a.php > d10b.py
// python3 d10b.py

?>
