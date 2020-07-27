<?php

define("WORKING_DIRECTORY", dirname(__DIR__));

require WORKING_DIRECTORY . "/vendor/autoload.php";

use App\Service\Config\PHPRCConfig;
use App\Controller\BashController;

if ( PHP_SAPI !== "cli" ) {
    echo "Must be run in cli" . PHP_EOL;
    echo "Exiting.." . PHP_EOL;
    exit(1);
}

$jsonConfig = json_decode(file_get_contents(WORKING_DIRECTORY . "/phprc_options.json"));

$config = (new PHPRCConfig)
    ->setScriptsDir(WORKING_DIRECTORY . "/" . $jsonConfig->scripts_dir)
    ->setSrcPath(WORKING_DIRECTORY . "/" . $jsonConfig->src_path)
    ->setVerbose($jsonConfig->verbose)
;

$bashController = new BashController($config);

$result = $bashController->load();

if ( !isset($result["success"]) && $config->isVerbose() ){
    echo "Generating file failed: " . ($result["error"] ?? "Unknown error") . PHP_EOL;
}

