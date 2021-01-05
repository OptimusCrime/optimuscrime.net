<?php
$rootDir = dirname(dirname(__FILE__));

if (!(include $rootDir . '/vendor/autoload.php')) {
    error_log('Dependencies are not installed!', E_USER_ERROR);
    header("HTTP/1.1 500 Internal Server Error");
    die();
}

use OptimusCrime\Site\Local\Server;

try {
    $server = new Server($rootDir);
    $server->run();
}
catch (Exception $e) {
    echo '<h1>Uncaught exception</h1>';
    var_dump($e->getMessage());
    var_dump($e->getTraceAsString());
    die();
}
