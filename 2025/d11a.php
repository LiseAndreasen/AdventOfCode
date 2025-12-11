<?php

///////////////////////////////////////////////////////////////////////////
// constants

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$datatmp = explode(" ", $line);
			// remove colon
			$begin_tmp = substr($datatmp[0], 0, -1);
			unset($datatmp[0]);
			$data[$begin_tmp] = $datatmp;
		}
	}
	return $data;
}

function take_step($data, $here, $there, $sofar, $part) {
	global $take_step_before;
	if(isset($take_step_before[$here][$there])) {
		return $take_step_before[$here][$there];
	}
	if(strcmp($here, $there) == 0) {
		// end reached
		// print($sofar . "\n");
		$take_step_before[$here][$there] = 1;
		return 1;
	}
	if(strcmp($here, "out") == 0) {
		// end reached, in a wrong way
		// print($sofar . "\n");
		$take_step_before[$here]["out"] = 0;
		return 0;
	}
	$sum = 0;
	foreach($data[$here] as $next) {
		$sum += take_step($data, $next, $there, $sofar . "*" . $next, $part);
	}
	$take_step_before[$here][$there] = $sum;
	return $sum;
}

// https://www.programmingalgorithms.com/algorithm/dijkstra's-algorithm/php/

$INT_MAX = 0x7FFFFFFF;

function MinimumDistance($distance, $shortestPathTreeSet, $verticesCount)
{
	global $INT_MAX;
	$min = $INT_MAX;
	$minIndex = 0;

	foreach($distance as $v => $whatever)
	{
		if ($shortestPathTreeSet[$v] == false && $distance[$v] <= $min)
		{
			$min = $distance[$v];
			$minIndex = $v;
		}
	}

	return $minIndex;
}

function PrintResult($distance, $verticesCount)
{
	echo "Vertex    Distance from source" . "\n";
	echo "svr" . "\t  " . $distance["svr"] . "\n";
	echo "fft" . "\t  " . $distance["fft"] . "\n";
	echo "dac" . "\t  " . $distance["dac"] . "\n";
	echo "out" . "\t  " . $distance["out"] . "\n";
}

function Dijkstra($graph, $source, $verticesCount)
{
	global $INT_MAX;
	$distance = array();
	$shortestPathTreeSet = array();

	foreach($graph as $i => $whatever)
	{
		$distance[$i] = $INT_MAX;
		$shortestPathTreeSet[$i] = false;
	}

	$distance[$source] = 0;

	for ($count = 0; $count < $verticesCount - 1; ++$count)
	{
		$u = MinimumDistance($distance, $shortestPathTreeSet, $verticesCount);
		$shortestPathTreeSet[$u] = true;

		foreach($graph as $v => $whatever)
			if (!$shortestPathTreeSet[$v] && $graph[$u][$v] && $distance[$u] != $INT_MAX && $distance[$u] + $graph[$u][$v] < $distance[$v])
				$distance[$v] = $distance[$u] + $graph[$u][$v];
	}

	PrintResult($distance, $verticesCount);
}

///////////////////////////////////////////////////////////////////////////
// main program

$input = file_get_contents('./d11input1n.txt', true);
$data = get_input($input);
// memoization
$take_step_before = array();

$sum = take_step($data, "you", "out", "you", 1);

printf("Result 1: %d\n\n", $sum);

$input = file_get_contents('./d11input1n.txt', true); // 1n or 2x
$data = get_input($input);
$take_step_before = array();

print("Time to analyze the input\n");

foreach($data as $source => $whatever1) {
	foreach($data as $dest => $whatever2) {
		// not connected yet
		$graph[$source][$dest] = 0;
	}
	$graph[$source]["out"] = 0;
}
foreach($data as $dest => $whatever2) {
	// not connected yet
	$graph["out"][$dest] = 0;
}
$graph["out"]["out"] = 0;
foreach($data as $source => $destinations) {
	foreach($destinations as $dest) {
		// make connection
		$graph[$source][$dest] = 1;
	}
}

Dijkstra($graph, "svr", sizeof($data));
Dijkstra($graph, "fft", sizeof($data));
Dijkstra($graph, "dac", sizeof($data));

// it's possible to get from fft to dac, but not the other way

// break the problem down to:
// a = number of routes from dac to out
// b = number of routes from fft to dac
// c = number of routes from svr to fft
// result = abc

$a = take_step($data, "dac", "out", "svr", 2);
$b = take_step($data, "fft", "dac", "svr", 2);
$c = take_step($data, "svr", "fft", "svr", 2);

printf("\nResult 2: %d\n", $a * $b * $c);
printf("(%d * %d * %d = %d)\n", $a, $b, $c, $a * $b * $c);

?>
