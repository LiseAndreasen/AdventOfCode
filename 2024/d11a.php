<?php

$input = file_get_contents('./d11input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(strlen($line)>2) {
		$num1 = explode(" ", $line);
	}
}

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

for($i=0;$i<25;$i++) { 
	for($j=0;$j<sizeof($num1);$j++) { 
		$num = $num1[$j];
		if($num == 0) {
			$num2[] = 1;
			continue;
		}
		$numl = strlen((string) $num);
		if($numl % 2 == 0) {
			$num2[] = (int) substr((string) $num, 0, $numl/2);
			$num2[] = (int) substr((string) $num, $numl/2, $numl/2);
			continue;
		}
		$num2[] = $num * 2024;
	} 
	$num1 = $num2;
	$num2 = array();
}

print(sizeof($num1)."\n");

?>
