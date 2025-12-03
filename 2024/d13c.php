<?php

$input = file_get_contents('./d13input2.txt', true);

$rules = array();
$i = 0;
foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(preg_match("/Button A/",$line,$matches)) {
		if(preg_match_all("/(\d+)/",$line,$matches)) {
			$rules[$i]["ax"] = $matches[0][0];
			$rules[$i]["ay"] = $matches[0][1];
		}
	}
	if(preg_match("/Button B/",$line,$matches)) {
		if(preg_match_all("/(\d+)/",$line,$matches)) {
			$rules[$i]["bx"] = $matches[0][0];
			$rules[$i]["by"] = $matches[0][1];
		}
	}
	if(preg_match("/Prize/",$line,$matches)) {
		if(preg_match_all("/(\d+)/",$line,$matches)) {
			$rules[$i]["px"] = $matches[0][0] + 10000000000000;
			$rules[$i]["py"] = $matches[0][1] + 10000000000000;
		}
	}
	if(strlen($line) < 2) {
		$i++;
	}
}

$money = 0;
$hits = 0;
foreach($rules as $rule) {
	// Coefficients of the equations
	$a1 = $rule["ax"]; $b1 = $rule["bx"]; $c1 = $rule["px"];
	$a2 = $rule["ay"]; $b2 = $rule["by"]; $c2 = $rule["py"];

	// Calculate the determinant
	$determinant = $a1 * $b2 - $a2 * $b1;

	if ($determinant == 0) {
//	    echo "The system has no unique solution (either no solution or infinitely many solutions).\n";
	} else {
	    // Calculate x and y using Cramer's Rule
	    $x = ($c1 * $b2 - $c2 * $b1) / $determinant;
	    $y = ($a1 * $c2 - $a2 * $c1) / $determinant;

//	    echo "The solution is: x = " . $x . ", y = " . $y . "\n";
	}
	
	$soln = $y;
	$solm = $x;
	if(abs((int)$soln - $soln) < 0.01 && abs((int)$solm - $solm) < 0.01) {
		if($solm > 0 && $soln > 0) {
			$money += $solm * 3 + $soln;
			$hits++;
		}
	}
}
print("$money\n");

?>
