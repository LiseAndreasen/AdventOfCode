<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d20input2.txt', true);

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$rule1 = explode(" -> ", $line);
			$rule_type = $rule1[0][0];
			$rule_name = substr($rule1[0], 1);
			if($rule_type != "%" && $rule_type != "&") {
				$rule_type = "0";
				$rule_name = $rule1[0];
			}
			$rule2 = explode(", ", $rule1[1]);
			$data[$rule_name] = [[$rule_name, $rule_type], $rule2];
		}
	}
	return $data;
}

function get_all_modules($data) {
	// 1st parse
	foreach($data as $rule) {
		$rule_in = $rule[0];
		[$rule_name, $rule_type] = $rule_in;
		$modules[$rule_name] = [$rule_name, $rule_type];
	}
	// 2nd parse
	foreach($data as $rule) {
		$rule_out = $rule[1];
		foreach($rule_out as $subrule) {
			if(!isset($modules[$subrule])) {
				$modules[$subrule] = [$subrule, "0"];
			}
		}
	}
	return $modules;
}

function push_button($data, $modules, &$states, &$memory, &$no_pulses, $i, &$cycles) {
	// send 1 low pulse
	$no_pulses["low"]++;
	foreach($data["broadcaster"][1] as $out) {
		$pulses[] = ["broadcaster", "low", $out];
		$no_pulses["low"]++;
	}
	// let pulse travel
	while(0 < sizeof($pulses)) {
		[$in, $phl, $pname] = array_shift($pulses);
		// detect possible cycle
		// only tg leads to rx
		if(strcmp($phl, "high") == 0 && strcmp($pname, "tg") == 0) {
			if(!isset($cycles[$in])) {
				$cycles[$in][0] = $i;
			}
			$cycles[$in][1][] = $i;
		}
		$mtype = $modules[$pname][1];
		if($mtype == "%") {
			if(strcmp($phl, "low") == 0) {
				// flip
				if(strcmp($states[$pname], "off") == 0) {
					$states[$pname] = "on";
				} else {
					$states[$pname] = "off";
				}
				if(strcmp($states[$pname], "off") == 0) {
					$pout = "low";
				} else {
					$pout = "high";
				}
				// and pulse travels
				foreach($data[$pname][1] as $out) {
					$pulses[] = [$pname, $pout, $out];
					$no_pulses[$pout]++;
				}
			}
		}
		if($mtype == "&") {
			$memory[$pname][$in] = $phl;
			// if all remembered states are high: low out
			$count = array_count_values($memory[$pname]);
			if(isset($count["high"])) {
				$no = $count["high"];
			} else {
				$no = 0;
			}
			if($no == sizeof($memory[$pname])) {
				$pout = "low";
			} else {
				$pout = "high";
			}
			// and pulse travels
			foreach($data[$pname][1] as $out) {
				$pulses[] = [$pname, $pout, $out];
				$no_pulses[$pout]++;
			}
		}
	}
	return $no_pulses;
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

// primarily list of all names and types
$modules = get_all_modules($data);

// init states
foreach($modules as $module) {
	[$name, $type] = $module;
	switch($type) {
		case "%":
			$states[$name] = "off";
			break;
		case "&":
			$states[$name] = "low";
			break;
	}
}
// init remembered states
foreach($data as $rule) {
	$rule_in = $rule[0];
	$rule_out = $rule[1];
	[$rule_name, $rule_type] = $rule_in;
	foreach($rule_out as $subrule) {
		if($modules[$subrule][1] == "&") {
			$memory[$subrule][$rule_name] = "low";
		}
	}
}

$no_pulses["low"] = 0;
$no_pulses["high"] = 0;
$cycles = [];

$max_i = 100000; // part 1: 1000, part 2: 10000
for($i=1;$i<=$max_i;$i++) {
	# states and memory and no_pulses and cycles will change
	push_button($data, $modules, $states, $memory, $no_pulses, $i, $cycles);
}

printf("Result 1: %d\n", $no_pulses["low"] * $no_pulses["high"]);

if(sizeof($cycles) == 4) {
	$prod = 1;
	foreach($cycles as $mname => $cycle) {
		printf("Control: %s, 1c = %5d, 10d = %5d, 20d = %5d\n", $mname, $cycle[1][0], $cycle[1][9], $cycle[1][19]);
		$prod *= $cycle[0];
	}
	printf("Result 2: %d\n", $prod);
}

?>
