<?php
namespace OptimusCrime\Views;

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Container;

use OptimusCrime\Post;
use OptimusCrime\Helpers\FeedParser;
use OptimusCrime\Helpers\PostParser;

class FrontpageView extends BaseView
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
        $postIds = FeedParser::getFeed($this->container->get('settings')['base_dir']);

        if (!is_array($postIds) or count($postIds) === 0) {
            return $this->render404($response);
        }

        $posts = [];
        foreach ($postIds as $v) {
            $posts[] = $this->postParser->getPost($v);
        }

        return $this->render($response, 'listing.tpl', [
            'POSTS_MODE' => BaseView::FRONTPAGE,
            'POSTS' => $posts,
            'POSTS_CONTENT_MODE' => Post::SHORT
        ]);
    }
}
