<?php
namespace OptimusCrime;

use \Slim\App as SlimApp;
use \Slim\Views\Smarty;
use \Slim\Views\SmartyPlugins;

class App
{
    private $app;

    public function __construct(array $settings)
    {
        session_start();
        $this->app = new SlimApp($settings);
    }

    public function run()
    {
        $this->routes();
        $this->dependencies();

        $this->app->run();
    }

    private function routes()
    {
        $this->app->get('/', '\OptimusCrime\Views\FrontpageView:view')->setName('home');
        $this->app->get('/{id:[0-9]+}-{alias:.*}', '\OptimusCrime\Views\PostView:view')->setName('post');

        // Old site
        $this->app->get('/blog/', '\OptimusCrime\Views\FrontpageView:view');
        $this->app->get('/blog/{id:[0-9]+}-{alias:.*}', '\OptimusCrime\Views\PostView:view');
    }

    private function dependencies()
    {
        $container = $this->app->getContainer();
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
