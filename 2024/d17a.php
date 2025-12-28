<?php

$input = file_get_contents('./d17input1.txt', true);

foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
	if(preg_match("/Register A/",$line,$matches)) {
		if(preg_match("/(\d+)/",$line,$matches)) {
			$a = $matches[0];
		}
	}
	if(preg_match("/Register B/",$line,$matches)) {
		if(preg_match("/(\d+)/",$line,$matches)) {
			$b = $matches[0];
		}
	}
	if(preg_match("/Register C/",$line,$matches)) {
		if(preg_match("/(\d+)/",$line,$matches)) {
			$c = $matches[0];
		}
	}
	if(preg_match("/Program/",$line,$matches)) {
		if(preg_match_all("/(\d+)/",$line,$matches)) {
			$prg = $matches[0];
		}
	}
}

$inspoi = 0;
$prgend = sizeof($prg);
$commas = "";
while($inspoi < $prgend) {

	$opcode = $prg[$inspoi];
	$operand = $prg[$inspoi+1];

/*
There are two types of operands; each instruction specifies the type of its operand. The value of a literal operand is the operand itself. For example, the value of the literal operand 7 is the number 7. The value of a combo operand can be found as follows:

    Combo operands 0 through 3 represent literal values 0 through 3.
    Combo operand 4 represents the value of register A.
    Combo operand 5 represents the value of register B.
    Combo operand 6 represents the value of register C.
    Combo operand 7 is reserved and will not appear in valid programs.
*/

	switch($operand) {
		case 0:
		case 1:
		case 2:
		case 3:
			$combo = $operand;
			break;
		case 4:
			$combo = $a;
			break;
		case 5:
			$combo = $b;
			break;
		case 6:
			$combo = $c;
			break;
	}

/*The adv instruction (opcode 0) performs division. The numerator is the value in the A register. The denominator is found by raising 2 to the power of the instruction's combo operand. (So, an operand of 2 would divide A by 4 (2^2); an operand of 5 would divide A by 2^B.) The result of the division operation is truncated to an integer and then written to the A register.*/

	if($opcode == 0) {
		$a = (int) ($a / pow(2, $combo));
	}

/*The bxl instruction (opcode 1) calculates the bitwise XOR of register B and the instruction's literal operand, then stores the result in register B.*/

	if($opcode == 1) {
		$b = $b ^ $operand;
	}

/*The bst instruction (opcode 2) calculates the value of its combo operand modulo 8 (thereby keeping only its lowest 3 bits), then writes that value to the B register.*/

	if($opcode == 2) {
		$b = $combo % 8;
	}

/*The jnz instruction (opcode 3) does nothing if the A register is 0. However, if the A register is not zero, it jumps by setting the instruction pointer to the value of its literal operand; if this instruction jumps, the instruction pointer is not increased by 2 after this instruction.*/

	if($opcode == 3) {
		if($a != 0) {
			$inspoi = $operand;
			continue;
		}
	}


/*The bxc instruction (opcode 4) calculates the bitwise XOR of register B and register C, then stores the result in register B. (For legacy reasons, this instruction reads an operand but ignores it.)*/

	if($opcode == 4) {
		$b = $b ^ $c;
	}

/*The out instruction (opcode 5) calculates the value of its combo operand modulo 8, then outputs that value. (If a program outputs multiple values, they are separated by commas.)*/

	if($opcode == 5) {
		$tmp = $combo % 8;
		echo "$commas$tmp";
		if($commas == "") {
			$commas = ",";
		}
	}


/*The bdv instruction (opcode 6) works exactly like the adv instruction except that the result is stored in the B register. (The numerator is still read from the A register.)*/

	if($opcode == 6) {
		$b = (int) ($a / pow(2, $combo));
	}

/*The cdv instruction (opcode 7) works exactly like the adv instruction except that the result is stored in the C register. (The numerator is still read from the A register.)*/

	if($opcode == 7) {
		$c = (int) ($a / pow(2, $combo));
	}

	$inspoi = $inspoi + 2;
}

echo "\n";

?>
