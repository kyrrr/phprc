<?php

#TODO: params

define("PHPRCDIR", dirname(__FILE__));

if ( PHP_SAPI !== "cli" ) {
	echo "Must be run in cli" . PHP_EOL;
	exit(1);
}



if ( !is_dir($scriptDir=(PHPRCDIR . "/scripts")) ){
	echo $scriptDir . " is not a dir" . PHP_EOL;
	exit(1);	
}


/**
* Creates an sh function from a php file path
* TODO: nested from path
*/
function createPhpShFunction(string $path){
	$functionName=pathinfo($path)["filename"];
	return  <<<FUNC
$functionName () {
    php $path;
}

FUNC;
}

/**
* Checks if the the supplied path is a file of type $ofExtension
*/
function isFile(string $file, string $ofExtension):bool{
	return is_file($file) && pathinfo($file)["extension"] === $ofExtension; 
}

#todo: recurse

$contents=scandir($scriptDir);
$functions="";

foreach ( $contents as $fileName ) {
	$fullPath = realpath($scriptDir . "/" . $fileName);
        if ( isFile($fullPath, "php") ) { #TODO: get extension
         	echo "Writing function from path " . $fileName . PHP_EOL;
		$functions .= createPhpShFunction($fullPath);
   	} else if (is_dir($fullPath) ) {}
}

$phprcFile = fopen($phprcFilePath=($_SERVER["HOME"] . "/.phprc"), "w+");
$written = fwrite($phprcFile, $functions);

echo "Wrote: " . $written . " bytes??" . PHP_EOL;
echo "To:  : " . $phprcFilePath . PHP_EOL;

echo "===" . PHP_EOL;
echo "Sourcing " . $phprcFilePath . PHP_EOL;
exec("source " . $phprcFilePath); 
