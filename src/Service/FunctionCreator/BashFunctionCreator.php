<?php


namespace App\Service\FunctionCreator;

use App\Entity\BashPHPRCFunction;
use SplFileObject;

class BashFunctionCreator implements PHPRCFunctionCreatorInterface
{
    protected $function;
    public function createFunction(string $path): BashPHPRCFunction
    {
        $file = new SplFileObject($path);
        return (new BashPHPRCFunction(
                $file->getBasename("." . $file->getExtension()),
                $file->getRealPath()
            ))
        ;
    }
}