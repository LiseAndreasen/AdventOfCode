<?php

$input = 
<<<END
xmul(2,4)&mul[3,7]!^don't()_mul(5,5)+mul(32,64](mul(11,8)undo()?mul(8,5))
END;

//$input = file_get_contents('./day3input.txt', true);

$sum = 0;
$doing = 1;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
  preg_match_all("/mul\(\d+,\d+\)|do\(\)|don't\(\)/", $line, $muls);

  foreach($muls[0] as $m) {
	if(preg_match("/don/", $m)) {
		$doing = 0;
	} else {
		if(preg_match("/do/", $m)) {
			$doing = 1;
		} else {
			if($doing == 1) {
				preg_match_all('/\d+/', $m, $nn);
				$n = $nn[0];
				$sum += $n[0] * $n[1];
			}
		}
	}
  }
}

echo $sum."\n";

?>
