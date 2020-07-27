<?php


namespace App\Service\Config;

class PHPRCConfig implements PHPRCConfigInterface
{
    protected
        $scriptsDir,
        $prefix,
        $verbose,
        $srcPath
    ;

    public function __construct(string $scriptsDir = null, string $prefix = null, string $srcPath = null, bool $verbose = false)
    {
        $this->scriptsDir = $scriptsDir;
        $this->prefix = $prefix;
        $this->srcPath = $srcPath;
        $this->verbose = $verbose;
    }

    public function setScriptsDir(string $dir): PHPRCConfigInterface
    {
        $this->scriptsDir = $dir;
        return $this;
    }

    public function getScriptsDir(): string
    {
        return $this->scriptsDir;
    }

    public function setPrefix(?string $prefix): PHPRCConfigInterface
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @return mixed
     */
    public function isVerbose():bool
    {
        return $this->verbose;
    }

    /**
     * @param bool $verbose
     * @return PHPRCConfig
     */
    public function setVerbose(bool $verbose):PHPRCConfigInterface
    {
        $this->verbose = $verbose;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSrcPath():string
    {
        return $this->srcPath;
    }

    /**
     * @param mixed $srcPath
     * @return PHPRCConfig
     */
    public function setSrcPath($srcPath):PHPRCConfigInterface
    {
        $this->srcPath = $srcPath;
        return $this;
    }



}