<?php


namespace App\Controller;

use App\Service\Config\PHPRCConfig;

abstract class Controller
{
    protected $config;
    public function __construct(PHPRCConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return array status code and messages
     */
    abstract function load():array;
}