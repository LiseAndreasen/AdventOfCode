<?php

$input = 
<<<END
xmul(2,4)%&mul[3,7]!@^do_not_mul(5,5)+mul(32,64]then(mul(11,8)mul(8,5))
END;

$sum = 0;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	preg_match_all("/mul\(\d+,\d+\)/", $line, $muls);

	foreach($muls[0] as $m) {
		preg_match_all('/\d+/', $m, $nn);
		$n = $nn[0];
		$sum += $n[0] * $n[1];
	}
}

echo $sum . "\n";

?>
