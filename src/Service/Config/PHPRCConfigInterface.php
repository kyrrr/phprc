<?php


namespace App\Service\Config;

interface PHPRCConfigInterface
{
    public function setScriptsDir(string $dir):PHPRCConfigInterface;
    public function getScriptsDir():string;

    public function setPrefix(?string $prefix):PHPRCConfigInterface;
    public function getPrefix():?string;

    public function setVerbose(bool $verbose):PHPRCConfigInterface;
    public function isVerbose():bool;

    public function setSrcPath(?string $path):PHPRCConfigInterface;
    public function getSrcPath():?string;
}