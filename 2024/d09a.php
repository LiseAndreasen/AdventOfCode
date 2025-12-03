<?php

$input = file_get_contents('./d9input1.txt', true);

$line = preg_split("/((\r?\n)|(\r\n?))/", $input);

$dmap = str_split($line[0]);

$lay = array(); // layout
$fno = 0; // file number
$fmode = 1; // file mode, 1 for file, 0 for empty space
for( $i = 0; $i < sizeof($dmap); $i += 1) { 
	if($fmode == 1) {
		for($j=0;$j<$dmap[$i];$j++) {
			$lay[] = $fno;
		}
		$fmode = 0;
		$fno++;
	} else {
		for($j=0;$j<$dmap[$i];$j++) {
			$lay[] = ".";
		}
		$fmode = 1;
	}
} 

$ffst = 0; // first file element not handled yet
$flst = sizeof($lay) - 1; // last file element not handled yet
while($lay[$ffst] != ".") {
	$ffst++;
}
while($lay[$flst] == ".") {
	$flst--;
}
while($ffst < $flst) {
	$lay[$ffst] = $lay[$flst];
	$lay[$flst] = ".";
	while($lay[$ffst] != ".") {
		$ffst++;
	}
	while($lay[$flst] == ".") {
		$flst--;
	}
}

$sum = 0;
$pos = 0;
while($lay[$pos] != ".") {
	$sum += $pos * $lay[$pos];
	$pos++;
}

print("$sum\n");

?>
