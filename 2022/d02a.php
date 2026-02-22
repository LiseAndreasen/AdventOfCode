<?php

///////////////////////////////////////////////////////////////////////////
// constants

$input = file_get_contents('./d02input2.txt', true);

$choice_opponent["A"] = "Rock";
$choice_opponent["B"] = "Paper";
$choice_opponent["C"] = "Scissors";

$choice_you["X"] = "Rock";
$choice_you["Y"] = "Paper";
$choice_you["Z"] = "Scissors";

// what beats M? N.
$beats["Rock"] = "Paper";
$beats["Paper"] = "Scissors";
$beats["Scissors"] = "Rock";

$points["Rock"] = 1;
$points["Paper"] = 2;
$points["Scissors"] = 3;

$points["loss"] = 0;
$points["draw"] = 3;
$points["win"] = 6;

$result["X"] = "loss";
$result["Y"] = "draw";
$result["Z"] = "win";

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			$data[] = explode(" ", $line);
		}
	}
	return $data;
}

function play($opp, $you) {
    global $beats;
    if(strcmp($opp, $you) == 0) {
        return "draw";
    }
    if(strcmp($beats[$opp], $you) == 0) {
        return "win"; // win for you
    }
    return "loss";
}

///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$score = 0;
foreach($data as $round) {
    [$opp, $you] = $round;
    $win_lose = play($choice_opponent[$opp], $choice_you[$you]);
    $score += $points[$choice_you[$you]] + $points[$win_lose];
}

printf("Result 1: %d\n", $score);

$score = 0;
foreach ($data as $round) {
    [$opp, $win_lose] = $round;
    if(strcmp($result[$win_lose], "draw") == 0) {
        $you = $opp;
        $score += $points[$choice_opponent[$you]] + $points["draw"];
    }
    if(strcmp($result[$win_lose], "win") == 0) {
        $you_choice = $beats[$choice_opponent[$opp]];
        $score += $points[$you_choice] + $points["win"];
    }
    if(strcmp($result[$win_lose], "loss") == 0) {
        $opp_choice = $choice_opponent[$opp];
        $you_choice = array_search($opp_choice, $beats);
        $score += $points[$you_choice] + $points["loss"];
    }
}

printf("Result 2: %d\n", $score);

?>
