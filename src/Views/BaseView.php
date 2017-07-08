<?php
namespace OptimusCrime\Views;

use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;

class BaseView
{
    const NONE = 0;
    const FRONTPAGE = 1;
    const POST = 2;

    private $templateData;
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->templateData = [
            'SITE_SETTINGS' => $this->container->get('settings')['site'],
            'SITE_TITLE' => 'OptimusCrime.net',

            'POSTS_MODE' => self::NONE,

            'MODE_FRONTPAGE' => self::FRONTPAGE,
            'MODE_POST' => self::POST
        ];
    }

    protected function setTemplateData($key, $value)
    {
        $this->templateData[$key] = $value;
    }

    protected function render(Response $response, $template, array $data = [])
    {
        return $this->container->get('view')->render($response, $template, array_merge(
            $this->templateData,
            $data
        ));
    }
    protected function render404(Response $response)
    {
        return $this->render($response, '404.tpl', [
            'SITE_TITLE' => 'Page not found! :: OptimusCrime.net'
        ]);
    }
}
