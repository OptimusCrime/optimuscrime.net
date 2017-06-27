<?php
if (PHP_SAPI == 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'])['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

use \OptimusCrime\Website;
use \OptimusCrime\Website\SettingsParser;


$settingsParser = new SettingsParser();
$settingsParser->parse([
    __DIR__ . '/../config/default-settings.php',
    __DIR__ . '/../config/settings.php'
]);
$app = new Website($settingsParser->getSettings());
$app->run();