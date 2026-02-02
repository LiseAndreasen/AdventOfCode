<?php

///////////////////////////////////////////////////////////////////////////
// constants

$test = 0;

if($test == 1) {
	$input = file_get_contents('./d24input1.txt', true);
} else {
	$input = file_get_contents('./d24input2.txt', true);
}

///////////////////////////////////////////////////////////////////////////
// functions

function get_input($input) {
	// absorb input file, line by line
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $input) as $line) {
		if(strlen($line)>2) {
			// 19, 13, 30 @ -2,  1, -2
			preg_match('#(.*), (.*), (.*) @ (.*), (.*), (.*)#SADi',
				$line, $m);
			[$all, $x, $y, $z, $vx, $vy, $vz] = $m;
			$data[] = [$x, $y, $z, $vx, $vy, $vz];
		}
	}
	return $data;
}

function find_crossings($data, $test) {
	// 2 points on a line: (x, y) and (x + vx, y + vy)
	// this line can also be described as
	// ax + b = y
	// a = vy / vx
	// b = y - x * vy / vx

	// 2 lines: a1 * x + b1 = y and a2 * x + b2 = y
	// these lines will cross at
	// (xc, yc), xc = (b2 - b1) / (a2 - a1), yc = a1 * xc + b1

	// we are going forwards in time
	// xc = x + k * vx, 0 < k -- goes for both lines

	// the crossing must also occur inside the test area
	if($test == 1) {
		$x_min = 7;
		$x_max = 27;
		$y_min = 7;
		$y_max = 27;
	} else {
		$x_min = 200000000000000;
		$x_max = 400000000000000;
		$y_min = 200000000000000;
		$y_max = 400000000000000;
	}

	$future_crossings = 0;
	foreach($data as $id1 => $stone1) {
		[$x1, $y1, $z1, $vx1, $vy1, $vz1] = $stone1;
		$a1 = $vy1 / $vx1;
		$b1 = $y1 - $x1 * $a1;
		
		foreach($data as $id2 => $stone2) {
			if($id1 >= $id2) {
				// only compare DIFFERENT stones
				// only compare the stones once
				continue;
			}
			
			[$x2, $y2, $z2, $vx2, $vy2, $vz2] = $stone2;
			$a2 = $vy2 / $vx2;
			$b2 = $y2 - $x2 * $a2;
			
if($test == 1) {
	printf("Hailstone A: $x1, $y1, $z1 @ $vx1, $vy1, $vz1\n");
	printf("Hailstone B: $x2, $y2, $z2 @ $vx2, $vy2, $vz2\n");
}
			
			if($a1 == $a2) {
				if($b1 == $b2) {
if($test == 1) {
	print("2 lines the same\n");
	print("\n");
}
				} else {
if($test == 1) {
	print("Hailstones' paths are parallel; they never intersect.\n");
	print("\n");
}
				}
				continue;
			}
			
			$xc = ($b1 - $b2) / ($a2 - $a1);
			$yc = $a1 * $xc + $b1;
			$k1 = ($xc - $x1) / $vx1;
			$k2 = ($xc - $x2) / $vx2;
			if($k1 < 0 || $k2 < 0) {
if($test == 1) {
	print("Hailstones' paths crossed in the past\n");
}
			} else {
				if($x_min <= $xc && $xc <= $x_max && $y_min <= $yc && $yc <= $y_max) {
if($test == 1) {
	printf("Hailstones' paths will cross inside the test area (at x=%6.3f, y=%6.3f).\n", $xc, $yc);
}
					$future_crossings++;
				} else {
if($test == 1) {
	printf("Hailstones' paths will cross outside the test area (at x=%6.3f, y=%6.3f).\n", $xc, $yc);
}
				}
			}
			
			if($test == 1) {
				print("\n");
			}
		}
	}
	
	return $future_crossings;
}


///////////////////////////////////////////////////////////////////////////
// main program

$data = get_input($input);

$future_crossings = find_crossings($data, $test);
	
//printf("Result 1: %d\n", $future_crossings);

// part 2
// we're looking for (xm, ym, zm, vxm, vym, vzm)
// such that for every stone at some time t
// (xm, ym, zm) + t(vxm, vym, vzm) = (x, y, z) + t(vx, vy, vz)

// and then I borrow from
// https://www.reddit.com/r/adventofcode/comments/18pnycy/comment/kepmry2/

// php d24a.php > d24b.py
// python3 d24b.py

print("import numpy as np
from sympy import Symbol
from sympy import solve_poly_system
x = Symbol('x')
y = Symbol('y')
z = Symbol('z')
vx = Symbol('vx')
vy = Symbol('vy')
vz = Symbol('vz')
equations = []
t_syms = []
");

for($i=0;$i<=2;$i++) {
	$shard_text = "[" . implode(",", $data[$i]) . "]";
	print("x0,y0,z0,xv,yv,zv = $shard_text
t = Symbol('t'+str($i))
eqx = x + vx*t - x0 - xv*t
eqy = y + vy*t - y0 - yv*t
eqz = z + vz*t - z0 - zv*t
equations.append(eqx)
equations.append(eqy)
equations.append(eqz)
t_syms.append(t)
");
}

print("result = solve_poly_system(equations,*([x,y,z,vx,vy,vz]+t_syms))
print(result)
print(result[0][0]+result[0][1]+result[0][2])
");

?>
