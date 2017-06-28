<?php
namespace OptimusCrime\Views;

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Container;

class Post extends Base
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
        print_r($args);
        return $this->render($response, 'post.tpl', [
        ]);
    }
}
