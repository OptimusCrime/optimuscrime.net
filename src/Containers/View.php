<?php
namespace OptimusCrime\Containers;

use \Slim\Container;
use \Slim\Views\Smarty;
use \Slim\Views\SmartyPlugins;

class View
{
    public static function load(Container $container)
    {
        $baseDir = $container->get('settings')['base_dir'];
        $container['view'] = function ($container) use ($baseDir) {
            $view = new Smarty($baseDir . '/templates', [
                'cacheDir' => $baseDir . '/smarty/cache',
                'compileDir' =>  $baseDir . '/smarty/compile',
            ]);

            $view->getSmarty()->setLeftDelimiter('[[+');
            $view->getSmarty()->setRightDelimiter(']]');
            $view->getSmarty()->setCaching(false);
            $view->getSmarty()->setDebugging(true);

            $smartyPlugins = new SmartyPlugins($container->get('router'), $container->get('request')->getUri());
            $view->registerPlugin('function', 'path_for', [$smartyPlugins, 'pathFor']);
            $view->registerPlugin('function', 'base_url', [$smartyPlugins, 'baseUrl']);

            return $view;
        };
    }
}
