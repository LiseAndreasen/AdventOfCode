<?php

$input = file_get_contents('./d11input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$num1 = explode(" ", $line);
	}
}

for($j=0;$j<sizeof($num1);$j++) {
	$num = $num1[$j];
	if(isset($num2[$num])) {
		$num2[$num]++;
	} else {
		$num2[$num] = 1;
	}
}
$num1 = $num2;
$num2 = array();

/*

    If the stone is engraved with the number 0, it is replaced by a stone
    engraved with the number 1.
    If the stone is engraved with a number that has an even number of digits,
    it is replaced by two stones. The left half of the digits are engraved on
    the new left stone, and the right half of the digits are engraved on the
    new right stone. (The new numbers don't keep extra leading zeroes: 1000
    would become stones 10 and 0.)
    If none of the other rules apply, the stone is replaced by a new stone;
    the old stone's number multiplied by 2024 is engraved on the new stone.

*/

for($i=0;$i<75;$i++) { 
	foreach($num1 as $num => $nom) { 
		if($num == 0) {
			if(isset($num2[1])) {
				$num2[1] += $nom;
			} else {
				$num2[1] = $nom;
			}
			continue;
		}
		$numl = strlen((string) $num);
		if($numl % 2 == 0) {
			$numa = (int) substr((string) $num, 0, $numl/2);
			if(isset($num2[$numa])) {
				$num2[$numa] += $nom;
			} else {
				$num2[$numa] = $nom;
			}
			$numb = (int) substr((string) $num, $numl/2, $numl/2);
			if(isset($num2[$numb])) {
				$num2[$numb] += $nom;
			} else {
				$num2[$numb] = $nom;
			}
			continue;
		}
		$numc = $num * 2024;
		if(isset($num2[$numc])) {
			$num2[$numc] += $nom;
		} else {
			$num2[$numc] = $nom;
		}
	} 
	$num1 = $num2;
	$num2 = array();
}

print(array_sum($num1)."\n");

?>
