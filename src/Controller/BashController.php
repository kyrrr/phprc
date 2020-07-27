<?php


namespace App\Controller;

use App\Service\FunctionCreator\BashFunctionCreator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class BashController extends Controller
{
    function load(): array
    {
        $path = realpath($this->config->getScriptsDir());
        $this->output("Recursively loading PHP scripts in " . $path);
        $directoryIterator = new RecursiveDirectoryIterator($path);
        $iteratorIterator = new RecursiveIteratorIterator($directoryIterator);
        $phpFiles = new RegexIterator($iteratorIterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
        $this->output("Found " . ($num=iterator_count($phpFiles)) . " PHP files");
        if ( $num === 0 ){
            return [
                "error" => "No scripts found"
            ];
        }
        $creator = new BashFunctionCreator();
        $file = "";
        foreach ($phpFiles as $index => $phpFile) {
            $this->output("Generating function from " . ($path=$phpFile[0]));
            $function = $creator->createFunction($path);
            $file .= $function->getOutput();
        }
        $openFile = fopen($outputFile=$this->config->getSrcPath(), "w+");
        $written = fwrite($openFile, $file);
        if ( $written ){
            $this->output("Wrote " . $written . " bytes to " . $outputFile);
            return [
                "success" => true,
            ];
        }
        return [
            "error" => "Failed to write to " . $outputFile
        ];
    }

    protected function output(string $message):void{
        if ( $this->config->isVerbose() ){
            echo $message . PHP_EOL;
        }
    }
}