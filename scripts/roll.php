<?php

$code = $argv[1] ?? null;

preg_match("/(\d+)[dD](\d+)([+-]\d+)?/", $code, $matches);

if ( !$code ) {
    die("Please supply a dice code.");
} else if ( !$matches ) {
    die("Supplied code does not match DnD dice pattern.");
}

array_shift($matches);

$rolls = (function(int $max, int $times, int $min = 1){
   for ( $i=0; $i < $times; $i++ ){
        $rolls[] = rand($min, $max);
   }
   return $rolls ?? []; 
})($matches[1], $matches[0]);

$sum = array_sum($rolls);
$mod = $matches[2] ?? null;

echo "You roll " . strtolower($code) . PHP_EOL;
echo "Rolled: " . implode(", ", $rolls) . PHP_EOL;

if ( $mod ){
    echo "Modify by: " . $mod . PHP_EOL;
    $sum += $mod;
}

echo "Sum: " . $sum . PHP_EOL;
