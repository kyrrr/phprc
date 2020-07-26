<?php

require "./vendor/autoload.php";

use App\Service\Config\PHPRCConfig;
use App\Controller\BashController;

if ( PHP_SAPI !== "cli" ) {
    echo "Must be run in cli" . PHP_EOL;
    echo "Exiting.." . PHP_EOL;
    exit(1);
}

define("WORKING_DIRECTORY", dirname(__FILE__));

$jsonConfig = json_decode(file_get_contents("./phprc_options.json"));
$config = new PHPRCConfig($jsonConfig->scripts_dir, $jsonConfig->prefix, $jsonConfig->src_path, $jsonConfig->verbose);
$bashController = new BashController($config);

$result = $bashController->load();

if ( !isset($result["success"]) ){
    echo "Generating file failed: " . ($result["error"] ?? "Unknown error") . PHP_EOL;
}

