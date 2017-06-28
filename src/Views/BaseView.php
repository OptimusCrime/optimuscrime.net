<?php
namespace OptimusCrime\Views;

use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;

class BaseView
{
    private $templateData;
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->templateData = [];
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
        ]);
    }
}
