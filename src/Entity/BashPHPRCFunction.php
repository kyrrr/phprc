<?php


namespace App\Entity;


class BashPHPRCFunction extends PHPRCFunction
{
    function generate(): string
    {
        return
            <<<FUNC
            
            # {$this->getComment()}
            {$this->getName()} () {
                php {$this->getPath()} $@;
            }
            
            FUNC;
    }
}