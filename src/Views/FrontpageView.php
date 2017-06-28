<?php
namespace OptimusCrime\Views;

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Container;

class FrontpageView extends BaseView
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function view(Request $request, Response $response, array $args)
    {
        return $this->render($response, 'home.tpl', [
        ]);
    }
}
