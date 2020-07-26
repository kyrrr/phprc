<?php


namespace App\Service\FunctionCreator;

use App\Entity\BashPHPRCFunction;

interface PHPRCFunctionCreatorInterface
{
    public function createFunction(string $path):BashPHPRCFunction;
}