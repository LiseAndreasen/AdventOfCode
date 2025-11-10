<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d17input2.txt', true);
$part = 2;

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		//print("$line\n");
		if(strlen($line)>2) {
			$map[] = str_split($line);
		}
	}
	return $map;
}

function print_map($map) {
	$map_height = sizeof($map);
	$map_width = sizeof($map[0]);
	for($j=0;$j<$map_height;$j++) {
		for($i=0;$i<$map_width;$i++) {
			echo $map[$j][$i];
		}
		echo "\n";
	}
	print("======================\n");
}

function move_and_add_state($cost, $x, $y, $dx, $dy, $distance) {
	// dx and dy represent the direction
	global $map, $map_height, $map_width, $end_pos, $seen_cost_by_state, $state_queues_by_cost;
	global $start;

	$x += $dx;
	$y += $dy;

	// don't go off the map
	if($x == -1 || $x == $map_width) {
		return;
	}
	if($y == -1 || $y == $map_height) {
		return;
	}
	
	// calculate new cost
	$new_cost = $cost + $map[$y][$x];
	
	// test whether we have reached the goal
	if($x == $end_pos[0] && $y == $end_pos[1]) {
		printf("Lowest cost: %d\n", $new_cost);
		$time_elapsed_secs = microtime(true) - $start;
		printf("Time elapsed, seconds: %f\n", $time_elapsed_secs);
		exit();
	}
	
	// create the structure for this state
	$state = array($x, $y, $dx, $dy, $distance);
	
	// test whether this state already exists
	if(!isset($seen_cost_by_state[$x][$y][$dx][$dy][$distance])) {
		// add the state to the q
		if(!isset($state_queues_by_cost[$new_cost])) {
			$state_queues_by_cost[$new_cost] = array();
		}
		$state_queues_by_cost[$new_cost][] = $state;
		
		// add to the list of visited states
		$seen_cost_by_state[$x][$y][$dx][$dy][$distance] = $new_cost;
	}
}

///////////////////////////////////////////////////////////////////////////
// main program

$start = microtime(true);

$map = get_input($input);
$map_height = sizeof($map);
$map_width = sizeof($map[0]);

$end_pos = array($map_width - 1, $map_height - 1);

// https://www.reddit.com/r/adventofcode/comments/18luw6q/2023_day_17_a_longform_tutorial_on_day_17/

// q of states to visit
$state_queues_by_cost = array();
// register the cost of each state, including position and direction
$seen_cost_by_state = array();

// add first position to the q
move_and_add_state(0, 0, 0, 1, 0, 1);
move_and_add_state(0, 0, 0, 0, 1, 1);

while(true) {
	// find lowest cost
	$current_cost = min(array_keys($state_queues_by_cost));
	// remove the states with this cost from the q
	$next_states = $state_queues_by_cost[$current_cost];
	unset($state_queues_by_cost[$current_cost]);
	foreach($next_states as $state) {
		$x = $state[0];
		$y = $state[1];
		$dx = $state[2];
		$dy = $state[3];
		$distance = $state[4];
		if($part == 1) {
			// turn left and right
			move_and_add_state($current_cost, $x, $y, $dy, -$dx, 1);
			move_and_add_state($current_cost, $x, $y, -$dy, $dx, 1);
			if($distance < 3) {
				// also move forward
				move_and_add_state($current_cost, $x, $y, $dx, $dy, $distance + 1);
			}
		} else {
			if($distance >= 4) {
				// turn left and right
				move_and_add_state($current_cost, $x, $y, $dy, -$dx, 1);
				move_and_add_state($current_cost, $x, $y, -$dy, $dx, 1);
			}
			if($distance < 10) {
				// also move forward
				move_and_add_state($current_cost, $x, $y, $dx, $dy, $distance + 1);
			}
		}
	}
}

?>
