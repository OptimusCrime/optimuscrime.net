<?php

use OptimusCrime\Site\Generator\Generator;

if (!(include __DIR__ . '/../vendor/autoload.php')) {
    error_log('Dependencies are not installed!', E_USER_ERROR);
    header("HTTP/1.1 500 Internal Server Error");
    die();
}

if (php_sapi_name() !== 'cli') {
    error_log('Script must be called from cli', \E_USER_ERROR);
    die('Script must be called from cli');
}

try {
    $generator = new Generator(dirname(__DIR__));
    $generator->run();
}
catch (Exception $e) {
    echo 'Uncaught exception' . PHP_EOL;
    var_dump($e->getMessage());
    var_dump($e->getTraceAsString());
    die();
}
