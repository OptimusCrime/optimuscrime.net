<?php
function handlePostImages($path) {
    $imagePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'posts' . DIRECTORY_SEPARATOR . 'static';
    $imagePath .= DIRECTORY_SEPARATOR . $path;

    if (!is_file($imagePath)) {
        return;
    }

    $info = pathinfo($imagePath);
    if (!isset($info['extension']) or (isset($info['extension']) and !in_array($info['extension'], ['png', 'jpg', 'jpeg']))) {
        return;
    }

    header('Content-Type: image/' . $info['extension']);

    if ($info['extension'] === 'png') {
        $im = imagecreatefrompng($imagePath);
        imagepng($im);
        die();
    }

    $im = imagecreatefromjpeg($imagePath);
    imagejpeg($im);
    die();
}

if (PHP_SAPI == 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'])['path'];

    // Handle static files as is (if exists)
    if (is_file($file)) {
        return false;
    }

    // Handle rewrite of static images
    preg_match('/\/public\/static\/(?P<path>.*)$/', $file, $matches, PREG_OFFSET_CAPTURE, 0);
    if (isset($matches['path']) and isset($matches['path'][0]) and strlen($matches['path'][0]) > 0) {
        handlePostImages($matches['path'][0]);
    }
}

require __DIR__ . '/../vendor/autoload.php';

use OptimusCrime\App;
use OptimusCrime\Helpers\SettingsParser;


$settingsParser = new SettingsParser();
$settingsParser->parse([
    __DIR__ . '/../config/default-settings.php',
    __DIR__ . '/../config/settings.php'
]);
$app = new App($settingsParser->getSettings());
$app->run();
