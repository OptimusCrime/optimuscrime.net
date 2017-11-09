<?php
namespace OptimusCrime\Views;

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Container;

use OptimusCrime\Post;
use OptimusCrime\Helpers\PostParser;

class PostView extends BaseView
{
    private $postParser;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->postParser = new PostParser($this->container->get('settings')['base_dir']);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function view(Request $request, Response $response, array $args)
    {
        if (!$this->postParser->postExists($args['id'])) {
            return $this->render404($response);
        }

        $post = $this->postParser->getPost($args['id']);
        if ($post === null or ($post instanceof Post and !$post->isPublished())) {
            return $this->render404($response);
        }

        return $this->render($response, 'listing.tpl', [
            'SITE_TITLE' => $post->getTitle() . ' :: OptimusCrime.net',
            'SITE_DESCRIPTION' => $post->getDescription(),
            'POSTS_MODE' => BaseView::POST,
            'POSTS' => [
                $post
            ],
            'POSTS_CONTENT_MODE' => Post::FULL
        ]);
    }
}
