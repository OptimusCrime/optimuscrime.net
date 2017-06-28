<?php
namespace OptimusCrime;

use \Slim\App as SlimApp;
use OptimusCrime\Loaders\Containers;

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
        $this->app->get('/', '\OptimusCrime\Views\Frontpage:view')->setName('home');
        $this->app->get('/{page:[0-9]+}-{alias:.*}', '\OptimusCrime\Views\Post:view')->setName('post');

        // Old site
        $this->app->get('/blog/', '\OptimusCrime\Views\Frontpage:view');
        $this->app->get('/blog/{page:[0-9]+}-{alias:.*}', '\OptimusCrime\Views\Post:view');
    }

    private function dependencies()
    {
        Containers::load($this->app->getContainer(), [
            \OptimusCrime\Containers\View::class,
        ]);
    }
}
