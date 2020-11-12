<?php

function getArgs(){
    array_shift($argv);
}

$args = [
  "algo" => $argv[1] ?? null,
  "hash" => $argv[2] ?? null,
  "target" => $argv[3] ?? null
];

foreach ($args as $arg){
    if ( is_null ( $arg ) ) {
	echo "Bad args!!" . PHP_EOL;
	exit(1);
    }
}

$targetHash = hash_file($args["algo"], $args["target"]);

if ( $targetHash === $args["hash"] ){
    echo "yes";
    exit(0);
}
echo "no";
exit(0);
