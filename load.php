<?php




$func = createFunctions($scriptDir);
var_dump($func);die;

$phprcFile = fopen($phprcFilePath=($_SERVER["HOME"] . "/.phprc"), "w+");
$written = fwrite($phprcFile, $functions);

echo "Wrote: " . $written . " bytes??" . PHP_EOL;
echo "To:  : " . $phprcFilePath . PHP_EOL;

echo "===" . PHP_EOL;
echo "Sourcing " . $phprcFilePath . PHP_EOL;
exec("source " . $phprcFilePath); 
