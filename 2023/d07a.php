<?php

/*
Every hand is exactly one type. From strongest to weakest, they are:
    Five of a kind, where all five cards have the same label: AAAAA
    Four of a kind, where four cards have the same label and one card
    	has a different label: AA8AA
    Full house, where three cards have the same label, and the remaining
    	two cards share a different label: 23332
    Three of a kind, where three cards have the same label, and the remaining
    	two cards are each different from any other card in the hand: TTT98
    Two pair, where two cards share one label, two other cards share a second
    	label, and the remaining card has a third label: 23432
    One pair, where two cards share one label, and the other three cards have
    	a different label from the pair and each other: A23A4
    High card, where all cards' labels are distinct: 23456
*/

// constants

// types of hand
$FioaK = 7;
$Fooak = 6;
$FH = 5;
$TooK = 4;
$TP = 3;
$OP = 2;
$HC = 1;

// structure of hands
$theHand = 0;
$theBid = 1;
$theType = 2;
$theRank = 3;

// functions

function gradeHands() {
	global $hands, $FioaK, $Fooak, $FH, $TooK, $TP, $OP, $HC, $theHand, $theType;
	foreach($hands as $key => $hand) {
		$handArr = str_split($hand[$theHand]);
		$uniq = array_count_values($handArr);
		sort($uniq);
		// 5 of a kind
		if(sizeof($uniq) == 1) {
			$hands[$key][$theType] = $FioaK;
			continue;
		}
		if(sizeof($uniq) == 2) {
			// 4 of a kind
			if($uniq[0] == 1 && $uniq[1] == 4) {
				$hands[$key][$theType] = $Fooak;
				continue;
			}
			// full house
			if($uniq[0] == 2 && $uniq[1] == 3) {
				$hands[$key][$theType] = $FH;
				continue;
			}
		}
		if(sizeof($uniq) == 3) {
			// 3 of a kind
			if($uniq[2] == 3) {
				$hands[$key][$theType] = $TooK;
				continue;
			}
			// 2 pairs
			if($uniq[2] == 2) {
				$hands[$key][$theType] = $TP;
				continue;
			}
		}
		// 1 pair
		if(sizeof($uniq) == 4) {
			$hands[$key][$theType] = $OP;
			continue;
		}
		// high card
		if(sizeof($uniq) == 5) {
			$hands[$key][$theType] = $HC;
			continue;
		}
	}
}

//////////////////////////////////////////////

$input = file_get_contents('./d07input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$tmp1 = explode(" ", $line);
		// exchange AKQJT with EDCBA
		$tmp2 = $tmp1[$theHand];
		$tmp2 = str_replace("A", "E", $tmp2);
		$tmp2 = str_replace("K", "D", $tmp2);
		$tmp2 = str_replace("Q", "C", $tmp2);
		$tmp2 = str_replace("J", "B", $tmp2);
		$tmp2 = str_replace("T", "A", $tmp2);
		$tmp1[$theHand] = $tmp2;
		$hands[] = $tmp1;
	}
}

// add type to each hand
gradeHands();

// create array with type + hand = rank
foreach($hands as $key => $hand) {
	$preRank[$key] = $hand[$theType] . $hand[$theHand];
}

// https://www.reddit.com/r/adventofcode/comments/1k0nia4/2023_day_7_part_1_php_help/

sort($preRank, SORT_STRING);

// mate hand with rank
foreach($hands as $key1 => $hand) {
	$handType = $hand[$theType];
	$handValue = $hand[$theHand];
	$key2 = array_search($handType . $handValue, $preRank);
	$hands[$key1][$theRank] = $key2 + 1;
}

// calculate winnings
$winnings = 0;
foreach($hands as $hand) {
	$winnings += $hand[$theBid] * $hand[$theRank];
}

print("Winnings: $winnings\n");

?>
